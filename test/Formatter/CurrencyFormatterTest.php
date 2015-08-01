<?php

namespace UAM\Twig\Extension\I18n\test\Formatter;

use Faker\Factory;
use NumberFormatter as IntlNumberFormatter;
use UAM\Twig\Extension\I18n\Formatter\NumberFormatter;
use UAM\Twig\Extension\I18n\Test\Formatter\AbstractFormatterTestCase;

class CurrencyFormatterTest extends AbstractFormatterTestCase
{
    /**
     * @dataProvider dataProvider
     */
    public function testFormatInteger($number, $locale, $currency)
    {
        $formatter = new IntlNumberFormatter($locale, IntlNumberFormatter::CURRENCY);

        $this->assertEquals(
            $this->getFormatter()->formatCurrency($number, $currency, $locale),
            $formatter->formatCurrency($number, $currency)
        );
    }

    public function dataProvider()
    {
        $faker = Factory::create();

        $data = array();

        for ($i = 1; $i <= 100; $i++) {
            $data[] = array(
                $faker->randomNumber(),
                $faker->currencyCode(),
                $faker->locale(),
            );
        }

        return $data;
    }

    protected function setupFormatter()
    {
        return new NumberFormatter();
    }
}
