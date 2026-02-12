<?php
// src/TboThemeBundle.php

namespace Tbo\TboThemeBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class TboThemeBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
