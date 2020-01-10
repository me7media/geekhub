<?php

namespace App\CatalogBundle\Twig;

use \Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class Catalog_Twig_Extension extends AbstractExtension
{

    /**
     * @return array
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('isfriday', [$this, 'isFridayToday']),
        ];
    }

    /**
     * @return array
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('price', [$this, 'formatPrice']),
        ];
    }

    /**
     * @param $number
     * @param int $decimals
     * @param string $decPoint
     * @param string $thousandsSep
     * @return string
     */
    public function formatPrice($number, $decimals = 0, $decPoint = '.', $thousandsSep = ',')
    {
        $price = number_format($number, $decimals, $decPoint, $thousandsSep);
        $price = '$'.$price;

        return $price;
    }

    /**
     * Check if parameter date is Friday or today is Friday if no parameter set
     * @param null $date
     * @return bool
     */
    public function isFridayToday($date = null)
    {
        $date = $this->isDate($date) ? $date : 'today';

        $today = new \DateTime($date);
        $day_of_week = $today->format('D');
        return $day_of_week == 'Fri';
    }

    /**
     * Check if the value is a valid date
     *
     * @param mixed $value
     *
     * @return boolean
     */
    public function isDate($value)
    {
        if (!$value) {
            return false;
        }

        try {
            new \DateTime($value);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

}