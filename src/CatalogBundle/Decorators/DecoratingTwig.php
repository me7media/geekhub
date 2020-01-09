<?php

namespace App\CatalogBundle\Decorators;


class DecoratingTwig
{

    private $twig;

    public function __construct($twig)
    {
        $this->twig = $twig;
    }

    public function getFilters()
    {
        return $this->twig->getFilters();
    }
}