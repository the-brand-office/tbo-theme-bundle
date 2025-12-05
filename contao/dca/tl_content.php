<?php

// Definition der Breakpoints für Bootstrap 5
$breakpoints = ['xs', 'sm', 'md', 'lg', 'xl', 'xxl'];

/**
 * Felder definieren
 */

// 1. Parent: Grid Auto Enable (Checkbox)
$GLOBALS['TL_DCA']['tl_content']['fields']['raven_grid_auto_enable'] = [
    'exclude'   => true,
    'inputType' => 'checkbox',
    'eval'      => ['submitOnChange' => true, 'tl_class' => 'w50 m12'],
    'sql'       => "char(1) NOT NULL default ''"
];

// 2. Child: Grid Specific Enable (Checkbox)
$GLOBALS['TL_DCA']['tl_content']['fields']['raven_grid_special_enable'] = [
    'exclude'   => true,
    'inputType' => 'checkbox',
    'eval'      => ['submitOnChange' => true, 'tl_class' => 'w50 m12'],
    'sql'       => "char(1) NOT NULL default ''"
];

// 3. Selektoren generieren (Sowohl für Auto als auch für Special)
foreach ($breakpoints as $bp) {
    // --- Felder für das ELTERN-Element (Auto Grid) ---
    // Erzeugt Klassen wie: ga-col-md-6 (muss im SCSS definiert werden als .grid.ga-col-md-6 > *)
    $fieldAuto = 'raven_grid_auto_' . $bp;
    $GLOBALS['TL_DCA']['tl_content']['fields'][$fieldAuto] = [
        'label'     => &$GLOBALS['TL_LANG']['tl_content']['raven_grid_auto_' . $bp],
        'exclude'   => true,
        'inputType' => 'select',
        'options'   => &$GLOBALS['TL_LANG']['tl_content']['raven_grid_columns'],
        'eval'      => [
            'includeBlankOption' => true, 
            'tl_class'           => 'w50', 
            'chosen'             => true
        ],
        'sql'       => "int(2) unsigned NOT NULL default 0"
    ];

    // --- Felder für das KIND-Element (Specific Grid) ---
    // Erzeugt Klassen wie: g-col-md-6 (Standard Bootstrap)
    $fieldSpecial = 'raven_grid_special_' . $bp;
    $GLOBALS['TL_DCA']['tl_content']['fields'][$fieldSpecial] = [
        'label'     => &$GLOBALS['TL_LANG']['tl_content']['raven_grid_special_' . $bp],
        'exclude'   => true,
        'inputType' => 'select',
        'options'   => &$GLOBALS['TL_LANG']['tl_content']['raven_grid_columns'],
        'eval'      => [
            'includeBlankOption' => true, 
            'tl_class'           => 'w50', 
            'chosen'             => true
        ],
        'sql'       => "int(2) unsigned NOT NULL default 0"
    ];
}

/**
 * Subpalettes definieren
 */
// Subpalette für den ELTERN-Container (Auto Grid)
$GLOBALS['TL_DCA']['tl_content']['palettes']['__selector__'][] = 'raven_grid_auto_enable';
$GLOBALS['TL_DCA']['tl_content']['subpalettes']['raven_grid_auto_enable'] = implode(',', array_map(fn($bp) => 'raven_grid_auto_' . $bp, $breakpoints));

// Subpalette für das KIND-Element (Specific Override)
$GLOBALS['TL_DCA']['tl_content']['palettes']['__selector__'][] = 'raven_grid_special_enable';
$GLOBALS['TL_DCA']['tl_content']['subpalettes']['raven_grid_special_enable'] = implode(',', array_map(fn($bp) => 'raven_grid_special_' . $bp, $breakpoints));

// Hinweis: Der onload_callback wurde entfernt und durch Raven\RavenThemeBundle\EventListener\DataContainer\ContentGridOptionsListener ersetzt.