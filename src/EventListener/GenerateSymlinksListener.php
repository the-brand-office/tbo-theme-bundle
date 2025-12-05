<?php

namespace Raven\RavenThemeBundle\EventListener; // WICHTIG: Passe den Namespace deines Bundles an!

use Contao\CoreBundle\Event\ContaoCoreEvents;
use Contao\CoreBundle\Event\GenerateSymlinksEvent;
use Psr\Log\LoggerInterface; // <-- Für Logging

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Filesystem\Filesystem; 

#[AsEventListener(ContaoCoreEvents::GENERATE_SYMLINKS)]
class GenerateSymlinksListener
{
    private $logger;
    private $filesystem;

    // Konstruktor-Injection des Loggers
    public function __construct(LoggerInterface $contaoCoreLogger)
    {
        // Wir verwenden den ContaoCoreLogger, um die Logs an die richtige Stelle zu senden
        $this->logger = $contaoCoreLogger;
        $this->filesystem = new Filesystem();
    }
    
    public function __invoke(GenerateSymlinksEvent $event): void
    {
        
        // --- Pfad-Definitionen ---
        $sourcePathCss = 'vendor/twbs/bootstrap/scss';
        $targetPathCss = 'files/raven5/scss/bootstrap';
        $targetDirCss = dirname($targetPathCss);

        $sourcePathJs = 'vendor/twbs/bootstrap/dist/js';
        $targetPathJs = 'files/raven5/js/bootstrap';
        $targetDirJs = dirname($targetPathJs);
        

        // -----------------------------------------------------------------------
        // 1. CSS-Symlink erstellen
        // -----------------------------------------------------------------------
        
        // 1a. ZIELVERZEICHNIS PRÜFEN UND ERSTELLEN
        if (!$this->filesystem->exists($targetDirCss)) {
            try {
                // Rekursive Erstellung mit korrekten Rechten
                $this->filesystem->mkdir($targetDirCss, 0777); 
            } catch (\Exception $e) {
                // Nur den Fehler loggen, falls die Erstellung fehlschlägt (z.B. wegen Rechten)
                $this->logger->error(sprintf('FEHLER: Zielverzeichnis konnte nicht erstellt werden: %s. Fehler: %s', $targetDirCss, $e->getMessage()));
                return; 
            }
        }

        // 1b. QUELLE PRÜFEN UND SYMLINK ERSTELLEN
        if ($this->filesystem->exists($sourcePathCss)) {
            $event->addSymlink($sourcePathCss, $targetPathCss);
        } else {
            // Nur warnen, wenn das Quellpaket nicht installiert ist
            $this->logger->warning(sprintf('QUELLE NICHT GEFUNDEN: Bootstrap SCSS Pfad existiert nicht: %s', $sourcePathCss));
        }

        // -----------------------------------------------------------------------
        // 2. JS-Symlink erstellen
        // -----------------------------------------------------------------------

        // 2a. ZIELVERZEICHNIS PRÜFEN UND ERSTELLEN (JS)
        if (!$this->filesystem->exists($targetDirJs)) {
            try {
                $this->filesystem->mkdir($targetDirJs, 0777);
            } catch (\Exception $e) {
                $this->logger->error(sprintf('FEHLER: Zielverzeichnis konnte nicht erstellt werden: %s. Fehler: %s', $targetDirJs, $e->getMessage()));
                return; 
            }
        }

        // 2b. QUELLE PRÜFEN UND SYMLINK ERSTELLEN (JS)
        if ($this->filesystem->exists($sourcePathJs)) {
            $event->addSymlink($sourcePathJs, $targetPathJs);
        } else {
            // Nur warnen, wenn das Quellpaket nicht installiert ist
            $this->logger->warning(sprintf('QUELLE NICHT GEFUNDEN: Bootstrap JS Pfad existiert nicht: %s', $sourcePathJs));
        }
        
    }
}