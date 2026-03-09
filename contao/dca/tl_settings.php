<?php

use Contao\CoreBundle\DataContainer\PaletteManipulator;

$GLOBALS['TL_DCA']['tl_settings']['fields']['tbo_element_widths'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_settings']['tbo_element_widths'],
    'inputType' => 'keyValueWizard',
    'eval' => ['tl_class' => 'clr'],
];

PaletteManipulator::create()
    ->addLegend('tbo_legend', 'title_legend', PaletteManipulator::POSITION_AFTER)
    ->addField('tbo_element_widths', 'tbo_legend', PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('default', 'tl_settings');
