<?php

namespace Tbo\TboThemeBundle\EventListener;

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
    public function __construct(
        private readonly Connection $connection
    ) {
    }

    #[AsCallback(table: "tl_page", target: "config.onsubmit_callback")]
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

     #[AsEventListener(event: "contao.data_container.oncut")]
     #[AsEventListener(event: "contao.data_container.oncopy")]
     #[AsEventListener(event: "contao.data_container.ondelete")]
     public function onPageOperation(): void
     {
         $this->recalculateAllHomepages();
     }

    private function recalculateAllHomepages(): void
    {
        $rootPageIds = $this->connection->fetchFirstColumn("SELECT id FROM tl_page WHERE type='root'");

        if (empty($rootPageIds)) {
            $this->connection->executeStatement("UPDATE tl_page SET is_homepage = ?", [""]);
            return;
        }

        foreach ($rootPageIds as $rootId) {
            $this->updateHomepageFlagForRoot((int) $rootId);
        }
    }

    private function updateHomepageFlagForRoot(int $rootId): void
    {
        $homepage = PageModel::findFirstPublishedByPid($rootId);

        $this->connection->executeStatement(
            "UPDATE tl_page SET is_homepage = ? WHERE pid = ?",
            ["", $rootId]
        );

        if (null !== $homepage) {
            $this->connection->executeStatement(
                "UPDATE tl_page SET is_homepage = ? WHERE id = ?",
                ["1", $homepage->id]
            );
        }
    }
}