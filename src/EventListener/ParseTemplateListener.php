<?php
// src/EventListener/ParseTemplateListener.php
namespace App\EventListener;

use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Contao\Template;

#[AsHook('parseTemplate')]
class ParseTemplateListener
{
    public function __invoke(Template $template): void
    {
        if ('fe_page' === $template->getName() || 0 === strpos($template->getName(), 'fe_page_')) {
            $template->foobar = 'foobar';
        }
        if (378 == $template->id ) {
				$template->cssID = 'a:2:{i:0;s:0:"";i:1;s:4:"test";}';
        }
		
		//$debug = $template->type;
		if(isset($debug) && \Contao\System::getContainer()->getParameter('kernel.debug')) dump($debug);

    }
}