# TBO Theme Bundle for Contao

Dieses Bundle stellt die grundlegende Funktionalität und Logik für das individuelle "TBO Theme" in Contao zur Verfügung.

## ⚠️ Wichtiger Hinweis zur Nutzung

Obwohl dieses Repository öffentlich zugänglich ist, ist das Bundle **ausschließlich für den internen Gebrauch** vorgesehen. 

Es handelt sich hierbei nicht um eine generische Erweiterung. Das Bundle baut stark auf spezifischen Einstellungen, Ordnerstrukturen und Konfigurationen des Theme-Setups auf. Ohne das entsprechende Contao Theme und die dazugehörigen Templates wird dieses Bundle in einer Standard-Contao-Installation nicht wie vorgesehen funktionieren oder sogar Fehler verursachen.

**Wir bieten für dieses Bundle keinen öffentlichen Support an.**

## KernFunktionen

- **SCSS- & JS-Lifecycle Management:** Automatische Generierung von Struktur-Symlinks für JavaScript- und SCSS-Core-Dateien.
- **Custom Templates:** Bereitstellung und Initiierung von projektbasierten SCSS-Vorlagen (Custom-Dateien), die Updates unberührt überstehen.
- Unterstützung und Integration spezifischer Funktionen für das dazugehörige Contao Frontend-Theme.

## Installation (Nur für interne Projekte)

Die Installation erfolgt über den Contao Manager oder über Composer:

```bash
composer require tbo/tbo-theme-bundle
```
