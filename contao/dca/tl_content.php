<?php

// Definition der Breakpoints und Defaults
$breakpoints = ['xs', 'sm', 'md', 'lg', 'xl', 'xxl'];
$gridDefaults = [
    'xs' => 12,
    'md' => 4,
    'xl' => 3,
    // Weitere Defaults hier ergänzen
];

/**
 * Felder definieren
 */

// 1. Parent: Grid Auto Enable (Checkbox)
$GLOBALS['TL_DCA']['tl_content']['fields']['tbo_grid_auto_enable'] = [
    'exclude' => true,
    'inputType' => 'checkbox',
    'eval' => ['submitOnChange' => true, 'tl_class' => 'w50 m12'],
    'sql' => "char(1) NOT NULL default ''"
];

// 2. Child: Grid Specific Enable (Checkbox)
$GLOBALS['TL_DCA']['tl_content']['fields']['tbo_grid_special_enable'] = [
    'exclude' => true,
    'inputType' => 'checkbox',
    'eval' => ['submitOnChange' => true, 'tl_class' => 'w50 m12'],
    'sql' => "char(1) NOT NULL default ''"
];
foreach ($breakpoints as $bp) {
    $defaultValue = $gridDefaults[$bp] ?? 0;

    // --- Felder für das ELTERN-Element (Auto Grid) ---
    $fieldAuto = 'tbo_grid_auto_' . $bp;
    $GLOBALS['TL_DCA']['tl_content']['fields'][$fieldAuto] = [
        'label' => &$GLOBALS['TL_LANG']['tl_content']['tbo_grid_auto_' . $bp],
        'exclude' => true,
        'inputType' => 'select',
        'default' => $defaultValue,
        'options' => &$GLOBALS['TL_LANG']['tl_content']['tbo_grid_columns'],
        'eval' => [
            'includeBlankOption' => true,
            'tl_class' => 'w50',
            'chosen' => true
        ],
        'sql' => "int(2) unsigned NOT NULL default " . $defaultValue
    ];

    // --- Felder für das KIND-Element (Specific Grid) ---
    // (Hier lassen wir die Defaults weg, da Overrides meist leer starten sollten)
    $fieldSpecial = 'tbo_grid_special_' . $bp;
    $GLOBALS['TL_DCA']['tl_content']['fields'][$fieldSpecial] = [
        'label' => &$GLOBALS['TL_LANG']['tl_content']['tbo_grid_special_' . $bp],
        'exclude' => true,
        'inputType' => 'select',
        'options' => &$GLOBALS['TL_LANG']['tl_content']['tbo_grid_columns'],
        'eval' => [
            'includeBlankOption' => true,
            'tl_class' => 'w50',
            'chosen' => true
        ],
        'sql' => "int(2) unsigned NOT NULL default 0"
    ];
}

/**
 * Subpalettes definieren
 */
// Wir drehen die Breakpoints für die Anzeige um (Mobile First -> Desktop First in der UI)
$reversedBreakpoints = array_reverse($breakpoints);

// Subpalette für den ELTERN-Container (Auto Grid)
$GLOBALS['TL_DCA']['tl_content']['palettes']['__selector__'][] = 'tbo_grid_auto_enable';
$GLOBALS['TL_DCA']['tl_content']['subpalettes']['tbo_grid_auto_enable'] = implode(',', array_map(fn($bp) => 'tbo_grid_auto_' . $bp, $reversedBreakpoints));

// Subpalette für das KIND-Element (Specific Override)
$GLOBALS['TL_DCA']['tl_content']['palettes']['__selector__'][] = 'tbo_grid_special_enable';
$GLOBALS['TL_DCA']['tl_content']['subpalettes']['tbo_grid_special_enable'] = implode(',', array_map(fn($bp) => 'tbo_grid_special_' . $bp, $reversedBreakpoints));

// Hinweis: Der onload_callback wurde entfernt und durch Tbo\TboThemeBundle\EventListener\DataContainer\ContentGridOptionsListener ersetzt.