<?php
declare(strict_types=1);

namespace Tbo\TboThemeBundle\EventListener\DataContainer;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\StringUtil;

#[AsCallback(table: 'tl_content', target: 'fields.tbo_element_width.options')]
class ContentElementWidthOptionsListener
{
    public function __invoke(): array
    {
        $options = [
            'width-fluid' => 'Volle Breite',
            'width-80' => '80% (Zentriert)',
            'width-60' => '60% (Zentriert)'
        ];

        // Eigene Breiten aus den Contao-Einstellungen hinzufügen
        $custom = \Contao\Config::get('tbo_element_widths');
        if ($custom) {
            $customArr = StringUtil::deserialize($custom, true);
            foreach ($customArr as $val) {
                if (!empty($val['key']) && !empty($val['value'])) {
                    $options[$val['key']] = $val['value'];
                }
            }
        }

        return $options;
    }
}
