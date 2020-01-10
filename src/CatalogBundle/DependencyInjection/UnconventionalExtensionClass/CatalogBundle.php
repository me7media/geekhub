<?php

namespace App\CatalogBundle\DependencyInjection\UnconventionalExtensionClass;


use App\CatalogBundle\DependencyInjection\CatalogBundleExtension;
use App\CatalogBundle\Twig\Catalog_Twig_Extension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class CatalogBundle extends Bundle
{

    public function boot()
    {
        parent::boot();
        $loader = new \Twig\Loader\FilesystemLoader(__DIR__.'/../Resources/views');
        $twig = new \Twig\Environment($loader);
        $twig->addExtension(new Catalog_Twig_Extension());
    }

    public function getContainerExtension()
    {
        parent::getContainerExtension();
        return new CatalogBundleExtension();
    }
}