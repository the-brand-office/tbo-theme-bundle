<?php
declare(strict_types=1);

namespace Tbo\TboThemeBundle\EventListener\Hook;

use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Contao\Template;

#[AsHook('parseTemplate')]
class ContentElementWidthClassListener
{
    public function __invoke(Template $template): void
    {
        $name = $template->getName();
        if (str_starts_with($name, 'ce_') || str_starts_with($name, 'content_element/')) {
            // Prüfung, ob die Breite im Datenbank-Record dieses CEs gesetzt wurde
            if (isset($template->tbo_element_width) && $template->tbo_element_width) {
                // In modernen Twig CEs (class Variable) anhängen
                $class = $template->class ?? '';
                $template->class = trim($class . ' ' . $template->tbo_element_width);
                
                // In Legacy CEs (cssID array) anhängen
                if (isset($template->cssID) && is_array($template->cssID)) {
                    $cssID = $template->cssID;
                    $cssID[1] = trim(($cssID[1] ?? '') . ' ' . $template->tbo_element_width);
                    $template->cssID = $cssID;
                }
            }
        }
    }
}
