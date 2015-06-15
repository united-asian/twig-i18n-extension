<?php

use UAM\Bundle\I18nBundle\Formatter\NumberFormatter;
use Symfony\Component\Intl\NumberFormatter\NumberFormatter as IntlNumberFormatter;

class NumberFormatTest extends PHPUnit_Framework_TestCase
{
    protected $locale = 'en';
    protected $formatter;
    protected $intlFormatter;

    public function setup()
    {
        $this->formatter = new NumberFormatter();
    }
    /**
     *@dataProvider numberProvider
     */
    public function testFormatNumber($number, $format, $expected)
    {
        $formatted = $this->formatter->formatNumber($number, $format);
        $this->assertEquals($expected, $formatted);
    }

    public function numberProvider()
    {
        $this->intlFormatter = new IntlNumberFormatter($this->locale, IntlNumberFormatter::DECIMAL);

        $data = array();

        for ($i = 1; $i <= 10; $i++) {
            $random_number = mt_rand(0, 1);
            $exponent = pow(10, $i);
            $number = $exponent * $random_number;
            $expected = $this->intlFormatter->format($number);
            $data[] = array($number, $this->locale, $expected);
        }

        return $data;
    }
    /**
     *@dataProvider percentProvider
     */
    public function testFormatPercent($number, $format, $expected)
    {
        $formatted = $this->formatter->formatPercent($number, $format);
        $this->assertEquals($expected, $formatted);
    }

    public function percentNumberProvider()
    {
        $this->intlFormatter = new IntlNumberFormatter($this->locale, IntlNumberFormatter::DECIMAL);

        $data = array();

        for ($i = 0; $i <= 10; $i++) {
            $random_number = mt_rand(0, 1);
            $exponent = pow(10, $i);
            $number = $exponent * $random_number;
            $percent_value = $number * 100;
            $expected = $this->intlFormatter->format($percent_value).'%';
            $data[] = array($number, $this->locale, $expected);
        }

        return $data;
    }
}
