<?php
declare(strict_types=1);
namespace Tbo\TboThemeBundle\Controller\FrontendModule;
use Contao\CoreBundle\Controller\FrontendModule\AbstractFrontendModuleController;
use Contao\CoreBundle\DependencyInjection\Attribute\AsFrontendModule;
use Contao\ModuleModel;
use Contao\PageModel;
use Contao\Validator;
use Contao\CoreBundle\Twig\FragmentTemplate;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Contao\CoreBundle\Image\Studio\Studio;
use Contao\CoreBundle\InsertTag\InsertTagParser;
use Contao\CoreBundle\File\Metadata;
use Contao\CoreBundle\String\HtmlAttributes;
#[AsFrontendModule(type: "logo", category: "tbo")]
class LogoController extends AbstractFrontendModuleController
{
    public function __construct(private readonly InsertTagParser $insertTagParser, private readonly Studio $studio) {}
    protected function getResponse(FragmentTemplate $template, ModuleModel $model, Request $request): Response
    {
        $linkUrl = (string) $model->tboUrl;
        if ($linkUrl) { $linkUrl = $this->insertTagParser->replaceInline($linkUrl ?? ""); }
        else {
            $currentPage = $request->attributes->get("pageModel");
            if ($currentPage) {
                $rootId = $currentPage->rootId; $homePage = PageModel::findFirstPublishedByPid($rootId);
                if ($homePage) $linkUrl = $homePage->getAbsoluteUrl(); else $linkUrl = "/";
            } else $linkUrl = "/";
        }
        if (Validator::isRelativeUrl($linkUrl)) $linkUrl = $request->getBasePath()."/".$linkUrl;
        $linkAttributes = (new HtmlAttributes())->set("href", $linkUrl);
        $figure = !$model->singleSRC ? null : $this->studio->createFigureBuilder()->fromUuid($model->singleSRC ?: "")->setSize($model->imgSize)->setLinkHref($linkUrl)->setLinkAttribute("rel", null, true)->setOverwriteMetadata(new Metadata(["caption" => ""]))->buildIfResourceExists();
        $template->set("image", $figure); $template->set("href", $linkUrl); $template->set("link_attributes", $linkAttributes); $template->set("element_css_classes", trim(($model->element_css_classes ?? "") . " logo" ));
        $template->set("fragment", "TboThemeBundle/frontend_module/logo.html.twig");
        return $template->getResponse();
    }
}