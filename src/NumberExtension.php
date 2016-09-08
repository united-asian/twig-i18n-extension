<?php

namespace UAM\Twig\Extension\I18n;

use NumberFormatter as IntlNumberFormatter;
use Twig_Extension;
use Twig_SimpleFilter;
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

    public function bytesFilter($bytes, $decimals, $unit)
    {
        $format_bytes = new NumberFormatter();

        return $format_bytes->formatBytes($bytes, $decimals, $unit);
    }
}
