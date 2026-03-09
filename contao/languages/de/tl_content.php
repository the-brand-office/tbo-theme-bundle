<?php

// Legenden
$GLOBALS['TL_LANG']['tl_content']['tbo_grid_parent_legend'] = 'Tbo Grid (Container)';
$GLOBALS['TL_LANG']['tl_content']['tbo_grid_child_legend'] = 'Tbo Grid (Kind-Element)';

// Checkboxes
$GLOBALS['TL_LANG']['tl_content']['tbo_grid_auto_enable'] = ['Automatisches Grid aktivieren', 'Verteilt die Spaltenbreite automatisch auf alle Kind-Elemente (ga-col-*).'];
$GLOBALS['TL_LANG']['tl_content']['tbo_grid_special_enable'] = ['Individuelle Spaltenbreite', 'Überschreibt die automatische Verteilung des Eltern-Grids für dieses Element.'];

// Grid Optionen (Wiederverwendbar)
$GLOBALS['TL_LANG']['tl_content']['tbo_grid_columns'] = [
    1 => 'Breite 1/12',
    2 => 'Breite 1/6',
    3 => 'Breite 1/4',
    4 => 'Breite 1/3',
    5 => 'Breite 5/12',
    6 => 'Breite 1/2',
    7 => 'Breite 7/12',
    8 => 'Breite 2/3',
    9 => 'Breite 3/4',
    10 => 'Breite 5/6',
    11 => 'Breite 11/12',
    12 => 'Breite 1/1 (Voll)',
];

// Breakpoints Labels generieren
$breakpoints = ['xs', 'sm', 'md', 'lg', 'xl', 'xxl'];

foreach ($breakpoints as $bp) {
    // Labels für Auto-Grid (Parent)
    $GLOBALS['TL_LANG']['tl_content']['tbo_grid_auto_' . $bp] = [
        strtoupper($bp) . ' - Standard Spaltenbreite',
        "Alle Kinder erhalten diese Breite ab Breakpoint $bp, sofern nicht überschrieben."
    ];

    // Labels für Specific-Grid (Child)
    $GLOBALS['TL_LANG']['tl_content']['tbo_grid_special_' . $bp] = [
        strtoupper($bp) . ' - Eigene Spaltenbreite',
        "Dieses Element erhält explizit diese Breite ab Breakpoint $bp."
    ];
}

// TBO Elemente
$GLOBALS['TL_LANG']['tl_content']['tbo_icon'] = ['Icon', 'Bitte wählen Sie ein Icon aus.'];

// TBO Content Elements CEs
$GLOBALS['TL_LANG']['tl_content']['tbo_text_icon'] = ['Text mit Icon', 'Erzeugt ein Textelement mit einem Icon.'];
$GLOBALS['TL_LANG']['tl_content']['tbo_link_icon'] = ['Hyperlink mit Icon', 'Erzeugt einen Link mit einem Icon.'];
$GLOBALS['TL_LANG']['tl_content']['tbo_teaser_box'] = ['Teaser-Box (Universal)', 'Universal-Element mit Bild, Icon und Button Optionen.'];

$GLOBALS['TL_LANG']['tl_content']['tbo_add_icon'] = ['Ein Icon hinzufügen', 'Dem Element ein Icon hinzufügen.'];
$GLOBALS['TL_LANG']['tl_content']['tbo_add_button'] = ['Einen Button hinzufügen', 'Dem Element einen Button (Hyperlink) hinzufügen.'];
$GLOBALS['TL_LANG']['tl_content']['tbo_element_width'] = ['Element-Breite (Layout)', 'Wählen Sie hier eine spezielle Breite für dieses Element aus. (Greift nur, wenn das Element nicht verschachtelt ist)'];
$GLOBALS['TL_LANG']['tl_content']['button_legend'] = 'Button-Einstellungen';
$GLOBALS['TL_LANG']['tl_content']['icon_legend'] = 'Icon-Einstellungen';