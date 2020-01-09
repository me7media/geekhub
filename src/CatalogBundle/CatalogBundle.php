<?php

namespace App\CatalogBundle;


use Symfony\Component\HttpKernel\Bundle\Bundle;

class CatalogBundle extends Bundle
{
    /**
     * @return string
     */
    public function getPublicDir(): string
    {
        return 'public/';
    }
}