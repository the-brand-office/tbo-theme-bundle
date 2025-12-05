<?php
// src/RavenThemeBundle.php

namespace Raven\RavenThemeBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class RavenThemeBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
