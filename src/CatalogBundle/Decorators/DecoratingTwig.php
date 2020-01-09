<?php

namespace App\CatalogBundle\Decorators;


use Twig\Environment;

class DecoratingTwig
{
    /**
     * @var Environment
     */
    private $twig;

    /**
     * DecoratingTwig constructor.
     * @param Environment $twig
     */
    public function __construct(Environment $twig)
    {

        $this->twig = $twig;
    }

    public function load($name)
    {
        return $this->twig->load($name);
    }

}