<?php
namespace Tbo\TboThemeBundle\EventListener;
use Contao\CoreBundle\Event\ContaoCoreEvents;
use Contao\CoreBundle\Event\GenerateSymlinksEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Filesystem\Filesystem;
#[AsEventListener(ContaoCoreEvents::GENERATE_SYMLINKS)]
class GenerateSymlinksListener
{
    private $logger; private $filesystem;
    public function __construct(LoggerInterface $contaoCoreLogger) { $this->logger = $contaoCoreLogger; $this->filesystem = new Filesystem(); }
    public function __invoke(GenerateSymlinksEvent $event): void
    {
        $sourcePathCss = "vendor/twbs/bootstrap/scss"; $targetPathCss = "files/theme/scss/bootstrap"; $targetDirCss = dirname($targetPathCss);
        $sourcePathJs = "vendor/twbs/bootstrap/dist/js"; $targetPathJs = "files/theme/js/bootstrap"; $targetDirJs = dirname($targetPathJs);
        if (!$this->filesystem->exists($targetDirCss)) { try { $this->filesystem->mkdir($targetDirCss, 0777); } catch (\Exception $e) { $this->logger->error(sprintf("FEHLER: Zielverzeichnis konnte nicht erstellt werden: %s. Fehler: %s", $targetDirCss, $e->getMessage())); return; } }
        if ($this->filesystem->exists($sourcePathCss)) $event->addSymlink($sourcePathCss, $targetPathCss);
        if (!$this->filesystem->exists($targetDirJs)) { try { $this->filesystem->mkdir($targetDirJs, 0777); } catch (\Exception $e) { $this->logger->error(sprintf("FEHLER: Zielverzeichnis konnte nicht erstellt werden: %s. Fehler: %s", $targetDirJs, $e->getMessage())); return; } }
        if ($this->filesystem->exists($sourcePathJs)) $event->addSymlink($sourcePathJs, $targetPathJs);
    }
}