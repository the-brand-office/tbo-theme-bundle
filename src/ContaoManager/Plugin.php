<?php

// src/ContaoManager/Plugin.php

namespace Raven\RavenThemeBundle\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Contao\ManagerPlugin\Config\ConfigPluginInterface;
use Contao\ManagerPlugin\Routing\RoutingPluginInterface;
use Raven\RavenThemeBundle\RavenThemeBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class Plugin implements BundlePluginInterface
{
    /**
     * Lädt das Haupt-Bundle.
     * Wird von C4 und C5 (Manager GUI) benötigt.
     */
    public function getBundles(ParserInterface $parser): array
    {
        return [
            BundleConfig::create(RavenThemeBundle::class)
                ->setLoadAfter([ContaoCoreBundle::class]),
        ];
    }

}

