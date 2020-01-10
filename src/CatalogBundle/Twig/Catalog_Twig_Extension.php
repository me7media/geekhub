<?php

namespace App\CatalogBundle\Twig;

use DateInterval;
use \Twig\Extension\AbstractExtension;
use Twig\Markup;
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
            new TwigFunction('loopweek', [$this, 'loopWeek']),
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

        $todayOrDate = new \DateTime($date);
        $day_of_week = $todayOrDate->format('D');
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

    /**
     * loop next 7 days in options tag for select tag
     * @param null $startdate - set start date
     * @param boolean $weekends - to render weekends
     * @param null $l - set user locale
     * @return string
     * @throws \Exception
     */
    public function loopWeek($startdate = null, $weekends = true, $l = null)
    {
        $locale = $l ? $l : @explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE'])[0] ?: 'us_US';
        setlocale(LC_TIME, $locale . '.UTF-8');

        $date = $this->isDate($startdate) ? $startdate : 'today';

        $todayOrDate = new \DateTime($date);

        $resultDays = [];
        for($i = 0; count($resultDays) < 7; $i++) {
            $todayOrDate->add(new DateInterval("P{$i}D"));
            $newDay = $todayOrDate->format('d F');
            $dbDay = $todayOrDate->format('Y-m-d');
            $weekDay = $todayOrDate->format('N');

            if($weekends || $weekDay < 6){
                $resultDays[$dbDay] = strftime("%e %B", strtotime($newDay));
            } elseif(!$weekends && $weekDay >= 6) {
                $i++;
            }
        }

        $result = '';
        foreach ($resultDays as $dbDate => $eachDay){
            $result .= "<option value='{$dbDate}'>{$eachDay}</option>";
        }
        return new Markup( $result, 'UTF-8' );
    }

}