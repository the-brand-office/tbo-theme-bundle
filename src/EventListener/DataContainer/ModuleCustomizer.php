<?php

namespace Raven\RavenThemeBundle\EventListener\DataContainer;

use Contao\DataContainer;
use Contao\ModuleModel;
use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback; // Import des Attributs
use Symfony\Component\HttpFoundation\RequestStack;

class ModuleCustomizer 
{
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }
	
	/**
     * Passt DCA-Flags typspezifisch an, bevor das Formular geladen wird.
     * Dies ersetzt den config.onload_callback.
     */
    #[AsCallback(table: 'tl_module', target: 'config.onload')]
    public function adjustFlagsOnLoad(DataContainer $dc): void
    {
       // Prüfung, ob wir den Datensatz überhaupt bearbeiten (nicht neu erstellen)
        if (null === $dc || !$dc->id || 'edit' !== $this->requestStack->getCurrentRequest()->query->get('act')) {
            return;
        }
		
		$model = ModuleModel::findByPk($dc->id);
		
        // Korrekte Prüflogik: Prüfen Sie direkt den Typ des aktiven Datensatzes
		if (null === $model || $model->type !== 'filtered_root_page_dependend_modules') {
			return;
		}
            
		$GLOBALS['TL_DCA'][$dc->table]['fields']['pages']['eval']['mandatory'] = false;
		$GLOBALS['TL_DCA'][$dc->table]['fields']['pages']['eval']['tl_class'] = 'clr w50';
		unset($GLOBALS['TL_DCA'][$dc->table]['fields']['pages']['eval']['isSortable']);
		
    }
    
}