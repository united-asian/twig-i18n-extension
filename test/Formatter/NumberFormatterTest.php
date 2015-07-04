<?php

namespace UAM\Twig\Extension\I18n\test\Formatter;

use NumberFormatter as IntlNumberFormatter;
use Faker\Factory;
use UAM\Twig\Extension\I18n\Formatter\NumberFormatter;
use UAM\Twig\Extension\I18n\Test\Formatter\AbstractFormatterTestCase;

class NumberFormatterTest extends AbstractFormatterTestCase
{
    /**
     * @dataProvider numberProvider
     */
    public function testFormatInteger($number, $locale, $decimals)
    {
        $formatter = new IntlNumberFormatter($locale, IntlNumberFormatter::DECIMAL);
        $formatter->setAttribute(IntlNumberFormatter::MIN_FRACTION_DIGITS, 0);
        $formatter->setAttribute(IntlNumberFormatter::MAX_FRACTION_DIGITS, 0);

        $this->assertEquals(
            $this->getFormatter()->formatInteger($number, $locale),
            $formatter->format($number)
        );
    }

    /**
     * @dataProvider numberProvider
     */
    public function testFormatNumber($number, $locale, $decimals)
    {
        $formatter = new IntlNumberFormatter($locale, IntlNumberFormatter::DECIMAL);
        $formatter->setAttribute(IntlNumberFormatter::MIN_FRACTION_DIGITS, 0);
        $formatter->setAttribute(IntlNumberFormatter::MAX_FRACTION_DIGITS, 0);

        $this->assertEquals(
            $this->getFormatter()->formatNumber($number, $locale),
            $formatter->format($number)
        );
    }

    /**
     * @dataProvider numberProvider
     */
    public function testFormatDecimal($number, $locale, $decimals)
    {
        $formatter = new IntlNumberFormatter($locale, IntlNumberFormatter::DECIMAL);
        $formatter->setAttribute(IntlNumberFormatter::MIN_FRACTION_DIGITS, $decimals);
        $formatter->setAttribute(IntlNumberFormatter::MAX_FRACTION_DIGITS, $decimals);

        $this->assertEquals(
            $this->getFormatter()->formatDecimal($number, $decimals, $locale),
            $formatter->format($number)
        );
    }

    /**
     * @dataProvider numberProvider
     */
    public function testFormatPercent($number, $locale, $decimals)
    {
        $formatter = new IntlNumberFormatter($locale, IntlNumberFormatter::PERCENT);
        $formatter->setAttribute(IntlNumberFormatter::MIN_FRACTION_DIGITS, $decimals);
        $formatter->setAttribute(IntlNumberFormatter::MAX_FRACTION_DIGITS, $decimals);

        $this->assertEquals(
            $this->getFormatter()->formatPercent($number, $decimals, $locale),
            $formatter->format($number)
        );
    }

    public function numberProvider()
    {
        $faker = Factory::create();

        $data = array();

        for ($i = 1; $i <= 10; $i++) {
            $data[] = array(
                mt_rand(0, 1) * pow(10, $i),
                $faker->locale(),
                $faker->numberBetween(0, 10)
            );
        }

        return $data;
    }

    protected function setupFormatter()
    {
        return new NumberFormatter();
    }
}
