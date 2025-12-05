<?php

namespace Raven\RavenBundle\EventListener;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\DataContainer;
use Contao\PageModel;
use Doctrine\DBAL\Connection;
use Symfony\Component\DependencyInjection\Attribute\AsService;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

/**
 * EventListener, der nach dem Speichern, Verschieben oder Löschen von Seiten
 * die Startseiten-Flags für alle Website-Startpunkte neu berechnet.
 */
#[AsService]
class PageListener
{
    /**
     * Verwendet Constructor Property Promotion (PHP 8.1+), um den Code zu verkürzen.
     * Die Eigenschaft $connection wird automatisch deklariert und zugewiesen.
     */
    public function __construct(
        private readonly Connection $connection
    ) {
    }

    /**
     * Diese Methode wird als onsubmit_callback für tl_page registriert.
     * @param DataContainer $dc Der DataContainer der bearbeiteten Seite.
     */
    #[AsCallback(table: 'tl_page', target: 'config.onsubmit_callback')]
    public function onPageSubmit(DataContainer $dc): void
    {
        if (!$dc->id) {
            return;
        }

        $pageModel = PageModel::findByPk($dc->id);

        if (null === $pageModel) {
            return;
        }
        
        $rootId = (int) $pageModel->rootId;
        
        if ($rootId > 0) {
            $this->updateHomepageFlagForRoot($rootId);
            return;
        }
        
        $this->recalculateAllHomepages();
    }

    /**
     * Diese Methode wird aufgerufen, wenn eine Seite verschoben, kopiert oder gelöscht wird.
     */
     #[AsEventListener(event: 'contao.data_container.oncut')]
     #[AsEventListener(event: 'contao.data_container.oncopy')]
     #[AsEventListener(event: 'contao.data_container.ondelete')]
     public function onPageOperation(): void
     {
         $this->recalculateAllHomepages();
     }

    /**
     * Iteriert über alle Website-Startpunkte und berechnet für jeden die Startseite neu.
     */
    private function recalculateAllHomepages(): void
    {
        $rootPageIds = $this->connection->fetchFirstColumn("SELECT id FROM tl_page WHERE type='root'");

        if (empty($rootPageIds)) {
            $this->connection->executeStatement('UPDATE tl_page SET is_homepage = ?', ['']);
            return;
        }

        foreach ($rootPageIds as $rootId) {
            $this->updateHomepageFlagForRoot((int) $rootId);
        }
    }

    /**
     * Setzt das 'is_homepage'-Flag für einen einzelnen Website-Startpunkt.
     */
    private function updateHomepageFlagForRoot(int $rootId): void
    {
        $homepage = PageModel::findFirstPublishedByPid($rootId);

        // Setze zuerst ALLE Seiten unter diesem Root zurück
        $this->connection->executeStatement(
            'UPDATE tl_page SET is_homepage = ? WHERE pid = ?',
            ['', $rootId]
        );

        if (null !== $homepage) {
            // Setze das Flag für die gefundene Startseite
            $this->connection->executeStatement(
                'UPDATE tl_page SET is_homepage = ? WHERE id = ?',
                ['1', $homepage->id]
            );
        }
    }
}

