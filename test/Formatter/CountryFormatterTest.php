<?php

namespace  UAM\Twig\Extension\I18n\test\Formatter;

use Locale;
use Faker\Factory;
use Symfony\Component\Intl\Intl;
use UAM\Twig\Extension\I18n\Formatter\CountryFormatter;
use UAM\Twig\Extension\I18n\Test\Formatter\AbstractFormatterTestCase;

class CountryFormatterTest extends AbstractFormatterTestCase
{
    /**
     * @dataProvider countryProvider
     */
    public function testFormatCountry($country, $locale)
    {
        $this->assertEquals(
            $this->getFormatter()->formatCountry($country, $locale),
            Intl::getRegionBundle()->getCountryName(strtoupper($country), $locale)
        );
    }

    /**
     * @dataProvider countryProvider
     */
    public function testFormatCountryWithDefaultLocale($country, $locale)
    {
        $this->assertEquals(
            $this->getFormatter()->formatCountry($country),
            Intl::getRegionBundle()->getCountryName(strtoupper($country), Locale::getDefault())
        );
    }

    public function countryProvider()
    {
        $faker = Factory::create();

        $countries = array();

        for ($i = 0; $i < 10; $i++) {
            $countries[] = array(
                $faker->countryCode(),
                $faker->locale()
            );
        }

        return $countries;
    }

    protected function setupFormatter()
    {
        return new CountryFormatter();
    }
}
