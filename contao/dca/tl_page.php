<?php

use Contao\CoreBundle\DataContainer\PaletteManipulator;

/**
 * Erweitert das DCA der tl_page Tabelle
 */

// --- Layout-Korrekturen für bestehende Felder ---

// Setzt die Klasse für das "Veröffentlichen"-Feld für das Layout mit 'is_homepage'
$GLOBALS['TL_DCA']['tl_page']['fields']['published']['eval']['tl_class'] = 'w50 clr';

// KORREKTUR 1: Fügt dem "Anzeigen ab"-Feld die 'clr'-Klasse hinzu.
// Dies erzwingt einen Zeilenumbruch, wenn das 'is_homepage'-Feld fehlt (z.B. bei Seitentyp 'root').
$GLOBALS['TL_DCA']['tl_page']['fields']['start']['eval']['tl_class'] = 'w50 clr';


// --- Feld: is_homepage (nur für reguläre Seiten) ---

$GLOBALS['TL_DCA']['tl_page']['fields']['is_homepage'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_page']['is_homepage'],
    'inputType' => 'checkbox',
    'eval'      => ['tl_class' => 'w50', 'disabled' => true],
    'sql'       => "char(1) NOT NULL default ''"
];

PaletteManipulator::create()
    ->addField('is_homepage', 'published', PaletteManipulator::POSITION_AFTER)
    ->applyToPalette('regular', 'tl_page');


// --- Felder: structured_search & search_url (nur für Seitentyp 'root') ---

$GLOBALS['TL_DCA']['tl_page']['fields']['structured_search'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_page']['structured_search'],
    'exclude'   => true,
    'inputType' => 'checkbox',
    'eval'      => ['tl_class' => 'w50 clr', 'submitOnChange' => true],
    'sql'       => "char(1) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_page']['fields']['search_url'] = [
    'label'      => &$GLOBALS['TL_LANG']['tl_page']['search_url'],
    'exclude'    => true,
    'inputType'  => 'pageTree',
    'foreignKey' => 'tl_page.title',
    'eval'       => ['fieldType' => 'radio', 'mandatory' => true],
    'sql'        => "int(10) unsigned NOT NULL default 0",
    'relation'   => ['type' => 'hasOne', 'load' => 'lazy']
];

// Subpalette für die neue Checkbox registrieren
$GLOBALS['TL_DCA']['tl_page']['palettes']['__selector__'][] = 'structured_search';
$GLOBALS['TL_DCA']['tl_page']['subpalettes']['structured_search'] = 'search_url';

// KORREKTUR 2: Das neue Feld wird jetzt korrekt nach dem Feld "maintenanceMode" platziert.
PaletteManipulator::create()
    ->addField('structured_search', 'maintenanceMode', PaletteManipulator::POSITION_AFTER)
    ->applyToPalette('root', 'tl_page')
	->applyToPalette('rootfallback', 'tl_page');

