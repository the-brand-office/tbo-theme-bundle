<?php

declare(strict_types=1);

namespace Raven\RavenThemeBundle\Controller\FrontendModule;

use Contao\CoreBundle\Controller\FrontendModule\RootPageDependentModulesController;
use Contao\CoreBundle\DependencyInjection\Attribute\AsFrontendModule;
use Contao\ModuleModel;
use Contao\PageModel;
use Contao\StringUtil;
use Contao\CoreBundle\Framework\ContaoFramework; 
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[AsFrontendModule('filtered_root_page_dependend_modules', category: 'raven')]
class FilteredRootPageDependendModulesController extends RootPageDependentModulesController
{
    private ContaoFramework $framework;

    // Dependency Injection: ContaoFramework wird benötigt, um PageModel und Controller-Methoden sicher aufzurufen
    public function __construct(ContaoFramework $framework)
    {
        // Da wir den Parent-Constructor nicht aufrufen, müssen wir die Abhängigkeiten hier setzen
        $this->framework = $framework;
    }
	
	public function __invoke(Request $request, ModuleModel $model, string $section, array|null $classes = null): Response
    {
        // 1. NEUE VOR-LOGIK (Ihre zusätzliche Seiten-Filterung)
        if (!$this->shouldDisplayBasedOnCustomPages($model)) {
            return new Response(); 
        }

        // 2. CORE-LOGIK WIEDERVERWENDEN
        // Wenn die Logik in __invoke() der Parent-Klasse liegt, rufen Sie diese auf.
        // Falls die Core-Klasse keine __invoke() hat, müssen Sie hier die Logik der Parent-Klasse kopieren.
        // Im Fall des Core-Controllers: Sie müssen die Logik kopieren, wie zuvor besprochen. 
        // ABER: Sie kopieren sie hier nur einmal, weil Sie ein neues Modul bauen.
        
        // Da wir die Core-Logik von RootPageDependentModulesController brauchen:
        return parent::__invoke($request, $model); // Wenn __invoke in Core vorhanden

        /* ODER, wenn __invoke() im Core nicht existiert:
        $controller = $this->framework->getAdapter(\Contao\Controller::class);
        $content = $controller->getFrontendModule($model);
        $this->tagResponse($model);
        return new \Symfony\Component\HttpFoundation\Response($content);
        */
    }
	
	/**
     * Prüft, ob das Modul basierend auf den Feldern "pages", "ravenInvert" und "ravenInherit"
     * auf der aktuellen Seite angezeigt werden soll.
     * * @param ModuleModel $model Das aktuelle Modul-Modell.
     * @return bool True, wenn das Modul angezeigt werden soll, False, wenn es versteckt werden soll.
     */
    private function shouldDisplayBasedOnCustomPages(ModuleModel $model): bool
    {
        // Framework initialisieren, um Contao-Klassen sicher zu nutzen
        $this->framework->initialize(PageModel::class, Controller::class);
        $pageAdapter = $this->framework->getAdapter(PageModel::class);
        $controllerAdapter = $this->framework->getAdapter(Controller::class);
        
        // 1. Daten des Modells auslesen
        // pages, ravenInvert, ravenInherit müssen als DCA-Felder in tl_module definiert sein!
        $pages = StringUtil::deserialize($model->pages, true);
        $invert = (bool)$model->ravenInvert;
        $inherit = (bool)$model->ravenInherit;
        
        // 2. Aktuelle Seite und Abbruchbedingungen
        $currentPage = $pageAdapter->getCurrent();
        
        // Wenn keine Seite geladen (z.B. Backend-Wildcard, obwohl __invoke das abfängt) 
        // oder keine Seiten im Modul ausgewählt: anzeigen.
        if (!$currentPage || empty($pages)) {
            return true;
        }

        // --- Filter-Logik ---
        
        $pageIdToCheck = $currentPage->id;
        
        // **Modus 1: NICHT vererbt ($inherit = false)**
        if (!$inherit) {
            $pageIsSelected = in_array($pageIdToCheck, $pages);
            
            // Invertiert: Wenn die Seite ausgewählt ist, AUSBLENDEN
            if ($invert) {
                return !$pageIsSelected; 
            }
            // Positiv: Wenn die Seite ausgewählt ist, ANZEIGEN
            else {
                return $pageIsSelected;
            }
        }
        
        // **Modus 2: Vererbt ($inherit = true)**
        else {
            // Holt alle IDs im Pfad (Trail) bis zur Root-Seite
            $trailIds = $controllerAdapter->getParentPages($pageIdToCheck); 
            $trailIds[] = $pageIdToCheck; // Aktuelle Seite selbst hinzufügen
            
            // Prüfen, ob EINE Seite im Trail ausgewählt ist
            $isPageInTrailSelection = false;
            foreach ($trailIds as $trailId) {
                if (in_array($trailId, $pages)) {
                    $isPageInTrailSelection = true;
                    break;
                }
            }
            
            // Invertiert und Vererbt: Ausblenden, wenn eine Seite im Trail zur Auswahl gehört
            if ($invert) {
                return !$isPageInTrailSelection;
            }
            
            // Positiv und Vererbt: Anzeigen, wenn eine Seite im Trail gefunden wurde
            else {
                return $isPageInTrailSelection;
            }
        }
    }
	
}
