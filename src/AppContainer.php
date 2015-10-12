<?php

namespace Learnosity;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class AppContainer
{
    private static $appContainer;

    public static function getApplicationContainer()
    {
        if (!self::$appContainer) {
            self::$appContainer = new ContainerBuilder();
            $loader = new YamlFileLoader(self::$appContainer, new FileLocator(__DIR__ . '/Config'));
            $loader->load('services.yml');
        }
        return self::$appContainer;
    }
}
