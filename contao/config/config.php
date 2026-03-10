<?php

/**
 * TBO Theme Bundle Configuration
 */

use Contao\Config;

// Default values for Fonts & Icons
// These are set if no value is present in localconfig.php or tl_config
if (Config::get('tbo_icon_font_name') === null) {
    Config::set('tbo_icon_font_name', 'tbo-icons');
}

if (Config::get('tbo_icon_font_path') === null) {
    Config::set('tbo_icon_font_path', 'bundles/tbotheme/fonts/icons/tbo-icons.svg');
}

if (Config::get('tbo_font_normal_name') === null) {
    Config::set('tbo_font_normal_name', 'Roboto');
}
if (Config::get('tbo_font_normal_file') === null) {
    Config::set('tbo_font_normal_file', 'roboto-latin-regular');
}

if (Config::get('tbo_font_bold_name') === null) {
    Config::set('tbo_font_bold_name', 'Roboto');
}
if (Config::get('tbo_font_bold_file') === null) {
    Config::set('tbo_font_bold_file', 'roboto-latin-700');
}

if (Config::get('tbo_font_italic_name') === null) {
    Config::set('tbo_font_italic_name', 'Roboto');
}
if (Config::get('tbo_font_italic_file') === null) {
    Config::set('tbo_font_italic_file', 'roboto-latin-italic');
}

if (Config::get('tbo_font_italic_bold_name') === null) {
    Config::set('tbo_font_italic_bold_name', 'Roboto');
}
if (Config::get('tbo_font_italic_bold_file') === null) {
    Config::set('tbo_font_italic_bold_file', 'roboto-latin-700italic');
}
