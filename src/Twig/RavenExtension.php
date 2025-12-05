<?php
# /src/Twig/RavenExtension.php


namespace Raven\RavenThemeBundle\Twig;

use Contao\ContentModel;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class RavenExtension extends AbstractExtension
{
	public function getFunctions(): array
    {
        return [
            new TwigFunction('getGridClassFromGroup', [$this, 'getGridClassFromGroup']),
        ];
    }

    public function getGridClassFromGroup($pid)
    {

        $debug = $parentModel = ContentModel::findByPk($pid);
		
		//$debug = $template->type;
		if(isset($debug) && \Contao\System::getContainer()->getParameter('kernel.debug')) dump($debug);

        if (null !== $parentModel && null !== $parentModel->class) {
            return $parentModel->class;
        }

        return '';
    }
}