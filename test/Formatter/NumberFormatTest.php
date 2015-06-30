<?php

namespace UAM\Twig\Extension\I18n\Test\Formatter;

use Symfony\Component\Intl\NumberFormatter\NumberFormatter as IntlNumberFormatter;
use UAM\Twig\Extension\I18n\Formatter\NumberFormatter;
use UAM\Twig\Extension\I18n\Test\Formatter\AbstractFormatterTestCase;

class NumberFormatTest extends AbstractFormatterTestCase
{
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
     *@dataProvider percentNumberProvider
     */
    public function testFormatPercent($number, $format, $expected)
    {
        $formatted = $this->formatter->formatPercent($number, $format);
        $this->assertEquals($expected, $formatted);
    }

    public function percentNumberProvider()
    {
        $data = array();

        for ($i = 0; $i <= 10; $i++) {
            $random_number = mt_rand(0, 1);
            $exponent = pow(10, $i);
            $number = $exponent * $random_number;
            $percent_value = $number * 100;
            $expected = $this->intlFormatter->format($percent_value).'%';
            $data[] = array($number,$this->locale, $expected);
        }

        return $data;
    }

    protected function getFormatter()
    {
        return new NumberFormatter();
    }

    protected function getIntlFormatter()
    {
        return new IntlNumberFormatter($this->locale, IntlNumberFormatter::DECIMAL);
    }

    protected function getLocale()
    {
        return ('en');
    }
}