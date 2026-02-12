<?php
declare(strict_types=1);
namespace Tbo\TboThemeBundle\Controller\FrontendModule;
use Contao\CoreBundle\Controller\FrontendModule\RootPageDependentModulesController;
use Contao\CoreBundle\DependencyInjection\Attribute\AsFrontendModule;
use Contao\ModuleModel;
use Contao\PageModel;
use Contao\StringUtil;
use Contao\Controller;
use Contao\CoreBundle\Framework\ContaoFramework;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
#[AsFrontendModule("filtered_root_page_dependend_modules", category: "tbo")]
class FilteredRootPageDependendModulesController extends RootPageDependentModulesController
{
    private ContaoFramework $framework;
    public function __construct(ContaoFramework $framework) { $this->framework = $framework; }
    public function __invoke(Request $request, ModuleModel $model, string $section, array|null $classes = null): Response
    {
        if (!$this->shouldDisplayBasedOnCustomPages($model)) return new Response();
        return parent::__invoke($request, $model);
    }
    private function shouldDisplayBasedOnCustomPages(ModuleModel $model): bool
    {
        $this->framework->initialize(PageModel::class, Controller::class); $pageAdapter = $this->framework->getAdapter(PageModel::class); $controllerAdapter = $this->framework->getAdapter(Controller::class);
        $pages = StringUtil::deserialize($model->pages, true); $invert = (bool)$model->tboInvert; $inherit = (bool)$model->tboInherit;
        $currentPage = $pageAdapter->getCurrent();
        if (!$currentPage || empty($pages)) return true;
        $pageIdToCheck = $currentPage->id;
        if (!$inherit) { $pageIsSelected = in_array($pageIdToCheck, $pages); return $invert ? !$pageIsSelected : $pageIsSelected; }
        else {
            $trailIds = $controllerAdapter->getParentPages($pageIdToCheck); $trailIds[] = $pageIdToCheck;
            $isPageInTrailSelection = false; foreach ($trailIds as $trailId) { if (in_array($trailId, $pages)) { $isPageInTrailSelection = true; break; } }
            return $invert ? !$isPageInTrailSelection : $isPageInTrailSelection;
        }
    }
}