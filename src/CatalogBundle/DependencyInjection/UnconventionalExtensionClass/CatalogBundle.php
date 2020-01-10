<?php

namespace App\CatalogBundle\DependencyInjection\UnconventionalExtensionClass;


use App\CatalogBundle\DependencyInjection\CatalogBundleExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class CatalogBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new CatalogBundleExtension();
    }
}