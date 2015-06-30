<?php

namespace  UAM\Twig\Extension\I18n\Test\Formatter;

use UAM\Twig\Extension\I18n\Formatter\CountryFormatter;
use Symfony\Component\Intl\Intl;
use UAM\Twig\Extension\I18n\Test\Formatter\AbstractFormatterTestCase;

abstract class AbstractCountryFormatterTestCase extends AbstractFormatterTestCase
{
    /**
     *@dataProvider CountryProvider
     */
    public function testFormatCountry($country, $expected)
    {
        $formatted = $this->formatter->formatCountry($country, $this->locale);
        $this->assertEquals($expected, $formatted);
    }

    public function countryProvider()
    {
        $countries = $this->intlFormatter->getCountryNames($this->locale);

        foreach ($countries as $id => $name) {
            $country =  $id;

            $expected = $name;

            $data[] = array($country, $expected);
            $data[] = array(strtolower($country), $expected);
        }

        return $data;
    }

    protected function getFormatter()
    {
        return new CountryFormatter();
    }

    protected function getIntlFormatter()
    {
        return Intl::getRegionBundle();
    }
}