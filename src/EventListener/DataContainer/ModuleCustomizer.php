<?php
namespace Tbo\TboThemeBundle\EventListener\DataContainer;
use Contao\DataContainer;
use Contao\ModuleModel;
use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Symfony\Component\HttpFoundation\RequestStack;
class ModuleCustomizer
{
    private $requestStack;
    public function __construct(RequestStack $requestStack) { $this->requestStack = $requestStack; }
    #[AsCallback(table: "tl_module", target: "config.onload")]
    public function adjustFlagsOnLoad(DataContainer $dc): void
    {
        if (null === $dc || !$dc->id || "edit" !== $this->requestStack->getCurrentRequest()->query->get("act")) return;
        $model = ModuleModel::findByPk($dc->id);
        if (null === $model || $model->type !== "filtered_root_page_dependend_modules") return;
        $GLOBALS["TL_DCA"][$dc->table]["fields"]["pages"]["eval"]["mandatory"] = false;
        $GLOBALS["TL_DCA"][$dc->table]["fields"]["pages"]["eval"]["tl_class"] = "clr w50";
        unset($GLOBALS["TL_DCA"][$dc->table]["fields"]["pages"]["eval"]["isSortable"]);
    }
}