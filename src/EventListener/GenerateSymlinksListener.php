<?php
namespace Tbo\TboThemeBundle\EventListener;
use Contao\CoreBundle\Event\ContaoCoreEvents;
use Contao\CoreBundle\Event\GenerateSymlinksEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
#[AsEventListener(ContaoCoreEvents::GENERATE_SYMLINKS)]
class GenerateSymlinksListener
{
    private $logger; private $filesystem;
    public function __construct(LoggerInterface $contaoCoreLogger) { $this->logger = $contaoCoreLogger; $this->filesystem = new Filesystem(); }
    public function __invoke(GenerateSymlinksEvent $event): void
    {
        $targetPathScss = "files/theme/scss";
        $targetPathJs = "files/theme/js";

        // Pre-create base directories
        foreach ([$targetPathScss, $targetPathJs] as $dir) {
            if (!$this->filesystem->exists($dir)) {
                try {
                    $this->filesystem->mkdir($dir, 0777);
                } catch (\Exception $e) {
                    $this->logger->error(sprintf("FEHLER: Zielverzeichnis konnte nicht erstellt werden: %s. Fehler: %s", $dir, $e->getMessage()));
                }
            }
        }

        // Bootstrap Symlinks
        $sourcePathBootstrapScss = "vendor/twbs/bootstrap/scss";
        $targetPathBootstrapScss = $targetPathScss . "/bootstrap";
        if ($this->filesystem->exists($sourcePathBootstrapScss)) $event->addSymlink($sourcePathBootstrapScss, $targetPathBootstrapScss);

        $sourcePathBootstrapJs = "vendor/twbs/bootstrap/dist/js";
        $targetPathBootstrapJs = $targetPathJs . "/bootstrap";
        if ($this->filesystem->exists($sourcePathBootstrapJs)) $event->addSymlink($sourcePathBootstrapJs, $targetPathBootstrapJs);

        // Symlink for TBO core SCSS files
        $sourcePathTboScss = "vendor/tbo/tbo-theme-bundle/contao/scss/tbo";
        $targetPathTboScss = $targetPathScss . "/tbo";
        if ($this->filesystem->exists($sourcePathTboScss)) $event->addSymlink($sourcePathTboScss, $targetPathTboScss);

        // Copy custom SCSS templates if they don't exist
        $sourcePathCustomScss = "vendor/tbo/tbo-theme-bundle/contao/scss/custom";
        
        if ($this->filesystem->exists($sourcePathCustomScss)) {
            $finder = new Finder();
            $finder->files()->in($sourcePathCustomScss);
            
            foreach ($finder as $file) {
                $targetFile = $targetPathScss . "/" . $file->getFilename();
                if (!$this->filesystem->exists($targetFile)) {
                    try {
                        $this->filesystem->copy($file->getRealPath(), $targetFile);
                    } catch (\Exception $e) {
                        $this->logger->error(sprintf("FEHLER: SCSS-Vorlage konnte nicht kopiert werden: %s. Fehler: %s", $file->getFilename(), $e->getMessage()));
                    }
                }
            }
        }
    }
}