<?php
declare(strict_types=1);
namespace Tbo\TboThemeBundle\EventListener\DataContainer;
use Contao\ContentModel;
use Contao\CoreBundle\DataContainer\PaletteManipulator;
use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\DataContainer;
#[AsCallback(table: "tl_content", target: "config.onload")]
class ContentGridOptionsListener
{
    private const PARENT_TYPES = ["element_group", "news_list", "faq_list"];
    public function __invoke(DataContainer $dc): void
    {
        if (!$dc->id) return;
        $element = ContentModel::findById($dc->id);
        if (!$element instanceof ContentModel) return;
        if (in_array($element->type, self::PARENT_TYPES, true)) {
            PaletteManipulator::create()->addLegend("tbo_grid_parent_legend", "template_legend", PaletteManipulator::POSITION_BEFORE)->addField("tbo_grid_auto_enable", "tbo_grid_parent_legend", PaletteManipulator::POSITION_APPEND)->applyToPalette($element->type, "tl_content");
        }
        if ("tl_content" === $dc->parentTable && $dc->currentPid) {
            $parentModel = ContentModel::findByPk($dc->currentPid);
            if ($parentModel instanceof ContentModel && $parentModel->tbo_grid_auto_enable) {
                PaletteManipulator::create()->addLegend("tbo_grid_child_legend", "expert_legend", PaletteManipulator::POSITION_BEFORE)->addField("tbo_grid_special_enable", "tbo_grid_child_legend", PaletteManipulator::POSITION_APPEND)->applyToPalette($element->type, "tl_content");
            }
        }
    }
}