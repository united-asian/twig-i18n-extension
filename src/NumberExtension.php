<?php

namespace UAM\Twig\Extension\I18n;

use Twig_Extension;
use Twig_SimpleFilter;
use NumberFormatter as IntlNumberFormatter;
use UAM\Twig\Extension\I18n\Formatter\NumberFormatter;

class NumberExtension extends Twig_Extension
{
    public function getName()
    {
        return 'number_extension';
    }

    public function getFilters()
    {
        return array(
            new Twig_SimpleFilter('bytes', array($this, 'bytesFilter'))
        );
    }

    public function bytesFilter($bytes, $unit, $locale)
    {
        $number_formatter = new NumberFormatter();

        return $number_formatter->formatBytes($bytes, $unit, $locale);
    }
}
