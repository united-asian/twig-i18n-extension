<?php

namespace UAM\Twig\Extension\I18n\test\Formatter;

use Faker\Factory;
use NumberFormatter as IntlNumberFormatter;
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

    /**
     * @dataProvider dataProvider()
     */
    public function testByteFilter($number, $format, $expected, $locale = null)
    {
        $this->assertEquals(
           $this->getFormatter()->formatBytes($number, $format, $locale),
           $expected
        );
    }

    public function dataProvider()
    {
        return array(
            array(1048576, 'b', '1048576b'),
            array(9728, 'Kb', '9Kb'),
            array(12897484, 'Mb', '12Mb'),
            array(16106127360, 'Gb', '15Gb'),
            array(7916483719987.2, 'Tb', '7Tb'),
            array(9002199254340800, 'Pb', '7Pb'),
            array(2048, 'Mb', '2Kb'),
            array(2048, 'h', '2Kb'),
            array(2056, 'b', '2056b'),
            array(600, 'b', '600b'),
            array('', 'Mb', '0b'),
        );
    }

    public function numberProvider()
    {
        $faker = Factory::create();

        $data = array();

        for ($i = 1; $i <= 10; ++$i) {
            $data[] = array(
                mt_rand(0, 1) * pow(10, $i),
                $faker->locale(),
                $faker->numberBetween(0, 10),
            );
        }

        return $data;
    }

    protected function setupFormatter()
    {
        return new NumberFormatter();
    }
}
