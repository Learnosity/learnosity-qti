<?php

namespace LearnosityQti;

use Exception;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class AppContainer
{
    private static ContainerBuilder $appContainer;

    public static function getApplicationContainer()
    {
        if (!self::$appContainer) {
            try {
                self::$appContainer = new ContainerBuilder();
                $loader = new YamlFileLoader(self::$appContainer, new FileLocator(__DIR__ . '/Config'));
                $loader->load('services.yml');
            } catch (Exception $e) {
                var_dump($e->getMessage());die;
            }
        }

        return self::$appContainer;
    }
}
