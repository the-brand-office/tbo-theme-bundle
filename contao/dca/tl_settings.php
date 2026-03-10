<?php

use Contao\CoreBundle\DataContainer\PaletteManipulator;

$GLOBALS['TL_DCA']['tl_settings']['fields']['tbo_element_widths'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_settings']['tbo_element_widths'],
    'inputType' => 'keyValueWizard',
    'eval' => ['tl_class' => 'clr'],
];

$GLOBALS['TL_DCA']['tl_settings']['fields']['tbo_icon_font_path'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_settings']['tbo_icon_font_path'],
    'inputType' => 'text',
    'eval' => ['tl_class' => 'w50', 'decodeEntities' => true],
];

$GLOBALS['TL_DCA']['tl_settings']['fields']['tbo_icon_font_name'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_settings']['tbo_icon_font_name'],
    'inputType' => 'text',
    'eval' => ['tl_class' => 'w50'],
];

// --- Web-Fonts ---
$fontFields = [
    'normal' => 'Standard',
    'bold' => 'Fett (Bold)',
    'italic' => 'Kursiv (Italic)',
    'italic_bold' => 'Fett-Kursiv (Bold-Italic)'
];

foreach ($fontFields as $key => $label) {
    $GLOBALS['TL_DCA']['tl_settings']['fields']['tbo_font_' . $key . '_name'] = [
        'label' => &$GLOBALS['TL_LANG']['tl_settings']['tbo_font_' . $key . '_name'],
        'inputType' => 'text',
        'eval' => ['tl_class' => 'w50'],
    ];
    $GLOBALS['TL_DCA']['tl_settings']['fields']['tbo_font_' . $key . '_file'] = [
        'label' => &$GLOBALS['TL_LANG']['tl_settings']['tbo_font_' . $key . '_file'],
        'inputType' => 'text',
        'eval' => ['tl_class' => 'w50'],
    ];
}

// Wir registrieren den Callback hier explizit im DCA, um sicherzugehen, dass Contao ihn bei DC_File triggert.
// Das PHP-Attribut #[AsCallback] allein scheint bei tl_settings (DC_File) manchmal nicht zuverlässig zu sein.
$GLOBALS['TL_DCA']['tl_settings']['config']['onsubmit_callback'][] = [\Tbo\TboThemeBundle\EventListener\DataContainer\SettingsFontListener::class, '__invoke'];

PaletteManipulator::create()
    ->addLegend('tbo_legend', 'title_legend', PaletteManipulator::POSITION_AFTER)
    ->addField(['tbo_element_widths'], 'tbo_legend', PaletteManipulator::POSITION_APPEND)
    ->addLegend('tbo_font_legend', 'tbo_legend', PaletteManipulator::POSITION_AFTER)
    ->addField(['tbo_icon_font_name', 'tbo_icon_font_path'], 'tbo_font_legend', PaletteManipulator::POSITION_APPEND)
    ->addField([
    'tbo_font_normal_name', 'tbo_font_normal_file',
    'tbo_font_bold_name', 'tbo_font_bold_file',
    'tbo_font_italic_name', 'tbo_font_italic_file',
    'tbo_font_italic_bold_name', 'tbo_font_italic_bold_file'
], 'tbo_font_legend', PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('default', 'tl_settings');