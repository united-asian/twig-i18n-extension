<?php

namespace UAM\Twig\Extension\I18n\test\Formatter;

use UAM\Twig\Extension\I18n\test\Formatter\AbstractFormatterTestCase;

class CountryFormatterTest extends AbstractFormatterTestCase
{

    public function testFormatCountry($country, $format, $expected) {

         $formatted = $this->formatter->formatCountry($country, $format);
         $this->assertEquals($expected, $formatted);
    }

    public function countryProvider()
    {
        $data = array();

        for ($i = 1; $i <= 10; $i++) {

        }

        return $data;
    }

    protected function getFormatter() {

    }

    protected function getIntlFormatter() {

    }

    protected function getLocale() {

    }
}
