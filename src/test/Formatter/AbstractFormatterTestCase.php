<?php

namespace UAM\Twig\Extension\I18n\test\Formatter;

use UAM\Twig\Extension\I18n\Formatter\NumberFormatter;
use PHPUnit_Framework_TestCase;

abstract class AbstractFormatterTestCase extends PHPUnit_Framework_TestCase
{
    protected $locale = 'en';
    protected $formatter;
    protected $intlFormatter;
    protected $format;

    public function setup()
    {
        $this->formatter = new NumberFormatter();
      
    }
    
}
    