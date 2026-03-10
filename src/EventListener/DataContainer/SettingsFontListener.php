<?php
declare(strict_types=1);

namespace Tbo\TboThemeBundle\EventListener\DataContainer;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\DataContainer;
use Contao\FrontendTemplate;
use Psr\Log\LoggerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Contao\Config;
use Contao\Input;

#[AsCallback(table: 'tl_settings', target: 'config.onsubmit')]
class SettingsFontListener
{
    private string $projectDir;
    private LoggerInterface $logger;

    public function __construct(string $projectDir, LoggerInterface $contaoCoreLogger)
    {
        $this->projectDir = $projectDir;
        $this->logger = $contaoCoreLogger;
    }

    public function __invoke(DataContainer $dc): void
    {
        // Wir erzwingen die Ausführung nur einmal pro Request
        static $executed = false;
        if ($executed) return;
        $executed = true;

        $this->logger->info('TBO: SettingsFontListener triggered');

        try {
            $template = new FrontendTemplate('tbo_dynamic_fonts');
            
            $fields = [
                'tbo_icon_font_name',
                'tbo_icon_font_path',
                'tbo_font_normal_name',
                'tbo_font_normal_file',
                'tbo_font_bold_name',
                'tbo_font_bold_file',
                'tbo_font_italic_name',
                'tbo_font_italic_file',
                'tbo_font_italic_bold_name',
                'tbo_font_italic_bold_file'
            ];

            foreach ($fields as $field) {
                $val = Input::post($field);
                
                // Logik für Deaktivierung oder Default:
                // 1. Wenn der User '-' eingibt, bleibt es beim '-', was im Template zum Deaktivieren führt.
                // 2. Wenn das Feld leer ist, greifen wir auf den Default aus der config.php zurück.
                if ($val === null || $val === '') {
                    $val = Config::get($field);
                }

                $template->$field = $val;
                
                // Wir persistieren den Wert explizit
                if ($val !== null) {
                    Config::set($field, $val);
                }
            }

            $content = $template->parse();
            $content = preg_replace('/<!-- TEMPLATE (START|END): .+ -->/', '', $content);
            $content = trim($content);

            $fs = new Filesystem();
            $destFile = $this->projectDir . '/files/theme/scss/_fonts-tbo.scss';
            
            $fs->dumpFile($destFile, $content);
            $this->logger->info('TBO: Generated SCSS and persisted settings (support for "-" skip)');
            
        } catch (\Exception $e) {
            $this->logger->error('TBO Font SCSS Generation failed: ' . $e->getMessage());
        }
    }
}
