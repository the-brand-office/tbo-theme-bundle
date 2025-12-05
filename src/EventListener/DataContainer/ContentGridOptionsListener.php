<?php

declare(strict_types=1);

namespace Raven\RavenThemeBundle\EventListener\DataContainer;

use Contao\ContentModel;
use Contao\CoreBundle\DataContainer\PaletteManipulator;
use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\DataContainer;

#[AsCallback(table: 'tl_content', target: 'config.onload')]
class ContentGridOptionsListener
{
    /**
     * Typen, die als Grid-Container fungieren (Parent-Logik)
     */
    private const PARENT_TYPES = ['element_group', 'news_list', 'faq_list'];

    public function __invoke(DataContainer $dc): void
    {
        // Ohne ID können wir das Model nicht laden (z.B. bei "Mehrere Bearbeiten" Initialisierung)
        if (!$dc->id) {
            return;
        }

        $element = ContentModel::findById($dc->id);

        if (!$element instanceof ContentModel) {
            return;
        }

        // 1. Logik: Ist das aktuelle Element ein Container?
        // Dann fügen wir die "Auto Grid" Konfiguration hinzu.
        if (in_array($element->type, self::PARENT_TYPES, true)) {
            PaletteManipulator::create()
                ->addLegend('raven_grid_parent_legend', 'template_legend', PaletteManipulator::POSITION_BEFORE)
                ->addField('raven_grid_auto_enable', 'raven_grid_parent_legend', PaletteManipulator::POSITION_APPEND)
                ->applyToPalette($element->type, 'tl_content');
        }

        // 2. Logik: Ist das aktuelle Element ein Kind eines anderen Elements?
        // Wir prüfen ptable (tl_content) und pid.
        if ('tl_content' === $dc->parentTable && $dc->currentPid) {
            $parentModel = ContentModel::findByPk($dc->currentPid);

            // Nur wenn der Eltern-Container auch das Raven-Grid aktiviert hat (Auto Enable),
            // bieten wir dem Kind die Möglichkeit, aus der Reihe zu tanzen.
            if ($parentModel instanceof ContentModel && $parentModel->raven_grid_auto_enable) {
                PaletteManipulator::create()
                    ->addLegend('raven_grid_child_legend', 'expert_legend', PaletteManipulator::POSITION_BEFORE)
                    ->addField('raven_grid_special_enable', 'raven_grid_child_legend', PaletteManipulator::POSITION_APPEND)
                    ->applyToPalette($element->type, 'tl_content');
            }
        }
    }
}