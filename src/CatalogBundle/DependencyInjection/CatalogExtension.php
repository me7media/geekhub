<?php

namespace App\CatalogBundle\DependencyInjection;


use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader;

class CatalogExtension extends Extension
{

    /**
     * @param array $configs
     * @param ContainerBuilder $container
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $fileLocator = new FileLocator(__DIR__.'/../Resources/config');

        $loader = new Loader\YamlFileLoader($container, $fileLocator);
        $loader->load('services.yaml');
//        $loader->load('security.yaml');
//        $loader->load('routes.yaml');

    }
}