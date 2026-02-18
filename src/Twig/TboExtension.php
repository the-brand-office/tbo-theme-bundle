<?php
# /src/Twig/TboExtension.php


namespace Tbo\TboThemeBundle\Twig;

use Contao\ContentModel;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TboExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('getGridClassFromGroup', [$this, 'getGridClassFromGroup']), // Keep for backward compat if needed, or remove? Plan implies refactoring. I'll keep it but redirect or deprecate. Actually, I'll just replace it with the new function in the template, so I can remove it here or keep it as alias. Let's add the new one and keep the old one for safety during transition if needed, but the user wants clean code. I'll replace the entry.
            new TwigFunction('getTboGridClasses', [$this, 'getTboGridClasses']),
        ];
    }

    /**
     * @param ContentModel|array $element
     */
    public function getTboGridClasses($element): string
    {
        if (!$element) {
            return '';
        }

        // Ensure we have an object or array access
        $isObject = is_object($element);
        
        $classes = [];
        $breakpoints = ['xs', 'sm', 'md', 'lg', 'xl', 'xxl'];

        // 1. Container Logic (e.g. valid for Group Elements)
        // Check `tbo_grid_auto_enable`
        $autoEnable = $isObject ? ($element->tbo_grid_auto_enable ?? false) : ($element['tbo_grid_auto_enable'] ?? false);

        if ($autoEnable) {
            $classes[] = 'grid';

            foreach ($breakpoints as $bp) {
                $fieldName = 'tbo_grid_auto_' . $bp;
                $value = (int) ($isObject ? ($element->$fieldName ?? 0) : ($element[$fieldName] ?? 0));
                
                if ($value > 0) {
                     // Pattern: tbo-grid-auto-{bp}-{cols}
                     // Exception: xs usually doesn't need bp prefix in some systems, but plan said: .tbo-grid-auto-md-4
                     // Let's stick to explicit: .tbo-grid-auto-xs-4 for consistency or check plan.
                     // Plan Example: .tbo-grid-auto-md-4. 
                     // SCSS Loop usually handles this.
                     $classes[] = 'tbo-grid-auto-' . $bp . '-' . $value;
                }
            }
        }

        // 2. Item Logic (Override settings)
        // Check `tbo_grid_special_enable`
        $specialEnable = $isObject ? ($element->tbo_grid_special_enable ?? false) : ($element['tbo_grid_special_enable'] ?? false);

        if ($specialEnable) {
            // Safety check: Only apply overrides if the parent actually has auto grid enabled
            $parentId = (int) ($isObject ? ($element->pid ?? 0) : ($element['pid'] ?? 0));
            $parentModel = ContentModel::findByPk($parentId);

            if ($parentModel && $parentModel->tbo_grid_auto_enable) {
                foreach ($breakpoints as $bp) {
                    $fieldName = 'tbo_grid_special_' . $bp;
                    $value = (int) ($isObject ? ($element->$fieldName ?? 0) : ($element[$fieldName] ?? 0));
                    
                    if ($value > 0) {
                        if ($bp === 'xs') {
                            $classes[] = 'g-col-' . $value;
                        } else {
                            $classes[] = 'g-col-' . $bp . '-' . $value;
                        }
                    }
                }
            }
        }

        return implode(' ', $classes);
    }

    // Deprecated / Legacy support if needed, but better to just remove logic if we update the template.
    public function getGridClassFromGroup($pid, $currentElementId = null)
    {
        return '';
    }
}