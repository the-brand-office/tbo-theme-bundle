<?php
namespace Tbo\TboThemeBundle\EventListener;
use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\Template;
class ParseTemplateListener
{
    #[AsCallback(table: "tl_content", target: "config.onparse")]
    public function onParseTemplate(Template $template): void {}
}