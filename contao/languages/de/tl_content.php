<?php

// Legenden
$GLOBALS['TL_LANG']['tl_content']['raven_grid_parent_legend'] = 'Raven Grid (Container)';
$GLOBALS['TL_LANG']['tl_content']['raven_grid_child_legend'] = 'Raven Grid (Kind-Element)';

// Checkboxes
$GLOBALS['TL_LANG']['tl_content']['raven_grid_auto_enable'] = ['Automatisches Grid aktivieren', 'Verteilt die Spaltenbreite automatisch auf alle Kind-Elemente (ga-col-*).'];
$GLOBALS['TL_LANG']['tl_content']['raven_grid_special_enable'] = ['Individuelle Spaltenbreite', 'Überschreibt die automatische Verteilung des Eltern-Grids für dieses Element.'];

// Grid Optionen (Wiederverwendbar)
$GLOBALS['TL_LANG']['tl_content']['raven_grid_columns'] = [
    1  => 'Breite 1/12',
    2  => 'Breite 1/6',
    3  => 'Breite 1/4',
    4  => 'Breite 1/3',
    5  => 'Breite 5/12',
    6  => 'Breite 1/2',
    7  => 'Breite 7/12',
    8  => 'Breite 2/3',
    9  => 'Breite 3/4',
    10 => 'Breite 5/6',
    11 => 'Breite 11/12',
    12 => 'Breite 1/1 (Voll)',
];

// Breakpoints Labels generieren
$breakpoints = ['xs', 'sm', 'md', 'lg', 'xl', 'xxl'];

foreach ($breakpoints as $bp) {
    // Labels für Auto-Grid (Parent)
    $GLOBALS['TL_LANG']['tl_content']['raven_grid_auto_' . $bp] = [
        strtoupper($bp) . ' - Standard Spaltenbreite', 
        "Alle Kinder erhalten diese Breite ab Breakpoint $bp, sofern nicht überschrieben."
    ];

    // Labels für Specific-Grid (Child)
    $GLOBALS['TL_LANG']['tl_content']['raven_grid_special_' . $bp] = [
        strtoupper($bp) . ' - Eigene Spaltenbreite', 
        "Dieses Element erhält explizit diese Breite ab Breakpoint $bp."
    ];
}