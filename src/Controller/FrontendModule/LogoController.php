<?php

declare(strict_types=1);

namespace Raven\RavenThemeBundle\Controller\FrontendModule;

use Contao\CoreBundle\Controller\FrontendModule\AbstractFrontendModuleController;
use Contao\CoreBundle\DependencyInjection\Attribute\AsFrontendModule;
use Contao\Controller;
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

#[AsFrontendModule(
    type: 'logo',
    category: 'raven'
)]
class LogoController extends AbstractFrontendModuleController
{

    public function __construct(
		private readonly InsertTagParser $insertTagParser, 
		private readonly Studio $studio
	)
    {}

    protected function getResponse(FragmentTemplate $template, ModuleModel $model, Request $request): Response
    {
		
        // 1. Link-URL verarbeiten
        $linkUrl = (string) $model->ravenUrl;

        if ($linkUrl) {
            // Link with attributes
			$linkUrl = $this->insertTagParser->replaceInline($linkUrl ?? '');

			
        } else {
			
			$currentPage = $request->attributes->get('pageModel');
            
            if ($currentPage) {
                // Die rootId der aktuellen Seite ermitteln
                $rootId = $currentPage->rootId;
                
                // Die erste veröffentlichte Seite unter der Wurzel-Seite finden (die Startseite)
                // Hier verwenden wir den Adapter, da findFirstPublishedByPid NICHT statisch ist.
                $homePage = PageModel::findFirstPublishedByPid($rootId);
                
                if ($homePage) {
                    // URL der Startseite generieren, um Aliase/Sprachen zu berücksichtigen
                    $linkUrl = $homePage->getAbsoluteUrl();
                } else {
                    // Fallback, wenn keine Startseite gefunden wird
                    $linkUrl = '/'; 
                }
            } else {
                // Fallback, wenn keine aktuelle Seite verfügbar ist
                $linkUrl = '/'; 
            }
		}

		if (Validator::isRelativeUrl($linkUrl)) {
			$linkUrl = $request->getBasePath().'/'.$linkUrl;
		}
		
        $linkAttributes = (new HtmlAttributes())
            ->set('href', $linkUrl)
        ;

        // 2. Bilddaten im Controller erstellen
		// Set figure and link
        $figure = !$model->singleSRC ? null : $this->studio
            ->createFigureBuilder()
            ->fromUuid($model->singleSRC ?: '')
            ->setSize($model->imgSize)
			->setLinkHref($linkUrl)
            ->setLinkAttribute('rel', null, true)
			->setOverwriteMetadata(new Metadata(['caption' => '']))
            ->buildIfResourceExists()
        ;


        // 3. Variablen an das Template übergeben
		$template->set('image', $figure);
        $template->set('href', $linkUrl);
        $template->set('link_attributes', $linkAttributes);
        $template->set('element_css_classes', trim(($model->element_css_classes ?? '') . ' logo' ));
		
		// Wir setzen den Template-Namen manuell, da die automatische Ableitung fehlschlagen kann.
        $template->set('fragment', 'RavenThemeBundle/frontend_module/logo.html.twig');


        return $template->getResponse();
    }
}