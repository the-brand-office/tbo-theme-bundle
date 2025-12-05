<?php
use \Contao\CoreBundle\DataContainer\PaletteManipulator;



/**
 * add grid-classes config array
 */ 

$GLOBALS['TL_DCA']['tl_settings']['fields']['raven_grid_labels'] = array
(
	'label'			=> ['Raven-Grid-Labels','Kommagetrennte Liste eingeben. Achtung: Die Anzahl der Elemente der Labels und aller Klassen-Definitionen muss identisch sein!'],
	'exclude' 		=> true,
	'inputType' 	=> 'text',
	'eval' 			=> ['tl_class'=> 'clr'],
	'load_callback' => [function(mixed $varValue) {
		if ($varValue == "")
    {
        $varDefault = 'Breite 1/12,Breite 1/6,Breite 1/4,Breite 1/3,Breite 5/12,Breite 1/2,Breite 7/12,Breite 4/6,Breite 3/4,Breite 5/6,Breite 11/12,Breite 1';
		Contao\Config::getInstance()->update("\$GLOBALS['TL_DCA']['tl_settings']['raven_grid_labels']",$varDefault);
        return $varDefault;
    }
    else{
         return $varValue;
    }
	}],
);
$GLOBALS['TL_DCA']['tl_settings']['fields']['raven_grid_classes_sm'] = array
(
	'label'			=> ['Raven-Grid-Klassen mobile','Kommagetrennte Liste eingeben'],
	'exclude' 		=> true,
	'inputType' 	=> 'text',
	'eval' 			=> ['tl_class'=> 'clr'],
	'load_callback' => [function(mixed $varValue) {
		if ($varValue == "")
    {
        $varDefault = 'col-sm-1,col-sm-2,col-sm-3,col-sm-4,col-sm-5,col-sm-6,col-sm-7,col-sm-8,col-sm-9,col-sm-10,col-sm-11,col-sm-12';
		Contao\Config::getInstance()->update("\$GLOBALS['TL_DCA']['tl_settings']['raven_grid_classes_sm']",$varDefault);
        return $varDefault;
    }
    else{
         return $varValue;
    }
	}],
);
$GLOBALS['TL_DCA']['tl_settings']['fields']['raven_grid_classes_md'] = array
(
	'label'			=> ['Raven-Grid-Klassen tablet','Kommagetrennte Liste eingeben'],
	'exclude' 		=> true,
	'inputType' 	=> 'text',
	'eval' 			=> ['tl_class'=> 'clr'],
	'load_callback' => [function(mixed $varValue) {
		if ($varValue == "")
    {
        $varDefault = 'col-md-1,col-md-2,col-md-3,col-md-4,col-md-5,col-md-6,col-md-7,col-md-8,col-md-9,col-md-10,col-md-11,col-md-12';
		Contao\Config::getInstance()->update("\$GLOBALS['TL_DCA']['tl_settings']['raven_grid_classes_md']",$varDefault);
        return $varDefault;
    }
    else{
         return $varValue;
    }
	}],
);
$GLOBALS['TL_DCA']['tl_settings']['fields']['raven_grid_classes_lg'] = array
(
	'label'			=> ['Raven-Grid-Klassen laptop','Kommagetrennte Liste eingeben'],
	'exclude' 		=> true,
	'inputType' 	=> 'text',
	'eval' 			=> ['tl_class'=> 'clr'],
	'load_callback' => [function(mixed $varValue) {
		if ($varValue == "")
    {
        $varDefault = 'col-lg-1,col-lg-2,col-lg-3,col-lg-4,col-lg-5,col-lg-6,col-lg-7,col-lg-8,col-lg-9,col-lg-10,col-lg-11,col-lg-12';
		Contao\Config::getInstance()->update("\$GLOBALS['TL_DCA']['tl_settings']['raven_grid_classes_lg']",$varDefault);
        return $varDefault;
    }
    else{
         return $varValue;
    }
	}],
);
$GLOBALS['TL_DCA']['tl_settings']['fields']['raven_grid_classes_xlg'] = array
(
	'label'			=> ['Raven-Grid-Klassen desktop','Kommagetrennte Liste eingeben'],
	'exclude' 		=> true,
	'inputType' 	=> 'text',
	'eval' 			=> ['tl_class'=> 'clr'],
	'load_callback' => [function(mixed $varValue) {
		if ($varValue == "")
    {
        $varDefault = 'col-xlg-1,col-xlg-2,col-xlg-3,col-xlg-4,col-xlg-5,col-xlg-6,col-xlg-7,col-xlg-8,col-xlg-9,col-xlg-10,col-xlg-11,col-xlg-12';
		Contao\Config::getInstance()->update("\$GLOBALS['TL_DCA']['tl_settings']['raven_grid_classes_xlg']",$varDefault);
        return $varDefault;
    }
    else{
         return $varValue;
    }
	}],
);
$GLOBALS['TL_DCA']['tl_settings']['fields']['raven_grid_offset_labels'] = array
(
	'label'			=> ['Raven-Grid-Offset-Labels','Kommagetrennte Liste eingeben. Achtung: Die Anzahl der Elemente der Labels und aller Klassen-Definitionen muss identisch sein!'],
	'exclude' 		=> true,
	'inputType' 	=> 'text',
	'eval' 			=> ['tl_class'=> 'clr'],
	'load_callback' => [function(mixed $varValue) {
		if ($varValue == "")
    {
        $varDefault = 'Offset 1/12,Offset 1/6,Offset 1/4,Offset 1/3,Offset 5/12,Offset 1/2,Offset 7/12,Offset 4/6,Offset 3/4,Offset 5/6,Offset 11/12,Offset 1';
		Contao\Config::getInstance()->update("\$GLOBALS['TL_DCA']['tl_settings']['raven_grid_offset_labels']",$varDefault);
        return $varDefault;
    }
    else{
         return $varValue;
    }
	}],
);
$GLOBALS['TL_DCA']['tl_settings']['fields']['raven_grid_offset_classes_sm'] = array
(
	'label'			=> ['Raven-Grid-Offset-Klassen mobile','Kommagetrennte Liste eingeben'],
	'exclude' 		=> true,
	'inputType' 	=> 'text',
	'eval' 			=> ['tl_class'=> 'clr'],
	'load_callback' => [function(mixed $varValue) {
		if ($varValue == "")
    {
        $varDefault = 'offset-sm-1,offset-sm-2,offset-sm-3,offset-sm-4,offset-sm-5,offset-sm-6,offset-sm-7,offset-sm-8,offset-sm-9,offset-sm-10,offset-sm-11,offset-sm-12';
		Contao\Config::getInstance()->update("\$GLOBALS['TL_DCA']['tl_settings']['raven_grid_offset_classes_sm']",$varDefault);
        return $varDefault;
    }
    else{
         return $varValue;
    }
	}],
);
$GLOBALS['TL_DCA']['tl_settings']['fields']['raven_grid_offset_classes_md'] = array
(
	'label'			=> ['Raven-Grid-Offset-Klassen mobile','Kommagetrennte Liste eingeben'],
	'exclude' 		=> true,
	'inputType' 	=> 'text',
	'eval' 			=> ['tl_class'=> 'clr'],
	'load_callback' => [function(mixed $varValue) {
		if ($varValue == "")
    {
        $varDefault = 'offset-md-1,offset-md-2,offset-md-3,offset-md-4,offset-md-5,offset-md-6,offset-md-7,offset-md-8,offset-md-9,offset-md-10,offset-md-11,offset-md-12';
		Contao\Config::getInstance()->update("\$GLOBALS['TL_DCA']['tl_settings']['raven_grid_offset_classes_md']",$varDefault);
        return $varDefault;
    }
    else{
         return $varValue;
    }
	}],
);
$GLOBALS['TL_DCA']['tl_settings']['fields']['raven_grid_offset_classes_lg'] = array
(
	'label'			=> ['Raven-Grid-Offset-Klassen mobile','Kommagetrennte Liste eingeben'],
	'exclude' 		=> true,
	'inputType' 	=> 'text',
	'eval' 			=> ['tl_class'=> 'clr'],
	'load_callback' => [function(mixed $varValue) {
		if ($varValue == "")
    {
        $varDefault = 'offset-lg-1,offset-lg-2,offset-lg-3,offset-lg-4,offset-lg-5,offset-lg-6,offset-lg-7,offset-lg-8,offset-lg-9,offset-lg-10,offset-lg-11,offset-lg-12';
		Contao\Config::getInstance()->update("\$GLOBALS['TL_DCA']['tl_settings']['raven_grid_offset_classes_lg']",$varDefault);
        return $varDefault;
    }
    else{
         return $varValue;
    }
	}],
);
$GLOBALS['TL_DCA']['tl_settings']['fields']['raven_grid_offset_classes_xlg'] = array
(
	'label'			=> ['Raven-Grid-Offset-Klassen mobile','Kommagetrennte Liste eingeben'],
	'exclude' 		=> true,
	'inputType' 	=> 'text',
	'eval' 			=> ['tl_class'=> 'clr'],
	'load_callback' => [function(mixed $varValue) {
		if ($varValue == "")
    {
        $varDefault = 'offset-xlg-1,offset-xlg-2,offset-xlg-3,offset-xlg-4,offset-xlg-5,offset-xlg-6,offset-xlg-7,offset-xlg-8,offset-xlg-9,offset-xlg-10,offset-xlg-11,offset-xlg-12';
		Contao\Config::getInstance()->update("\$GLOBALS['TL_DCA']['tl_settings']['raven_grid_offset_classes_xlg']",$varDefault);
        return $varDefault;
    }
    else{
         return $varValue;
    }
	}],
);

PaletteManipulator::create()
	// add a new "custom_legend" before the "date_legend"
	->addLegend('Raven Grid Einstellungen', 'chmod_legend', PaletteManipulator::POSITION_AFTER)
	
	// directly add new fields to the new legend
	->addField('raven_grid_labels', 'Raven Grid Einstellungen', PaletteManipulator::POSITION_APPEND)
	->addField('raven_grid_classes_sm', 'Raven Grid Einstellungen', PaletteManipulator::POSITION_APPEND)
	->addField('raven_grid_classes_md', 'Raven Grid Einstellungen', PaletteManipulator::POSITION_APPEND)
	->addField('raven_grid_classes_lg', 'Raven Grid Einstellungen', PaletteManipulator::POSITION_APPEND)
	->addField('raven_grid_classes_xlg', 'Raven Grid Einstellungen', PaletteManipulator::POSITION_APPEND)
	->addField('raven_grid_offset_labels', 'Raven Grid Einstellungen', PaletteManipulator::POSITION_APPEND)
	->addField('raven_grid_offset_classes_sm', 'Raven Grid Einstellungen', PaletteManipulator::POSITION_APPEND)
	->addField('raven_grid_offset_classes_md', 'Raven Grid Einstellungen', PaletteManipulator::POSITION_APPEND)
	->addField('raven_grid_offset_classes_lg', 'Raven Grid Einstellungen', PaletteManipulator::POSITION_APPEND)
	->addField('raven_grid_offset_classes_xlg', 'Raven Grid Einstellungen', PaletteManipulator::POSITION_APPEND)

	->applyToPalette('default', 'tl_settings');
