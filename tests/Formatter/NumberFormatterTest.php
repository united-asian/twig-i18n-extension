<?php

namespace UAM\Twig\Extension\I18n\Test\Formatter;

use Faker\Factory;
use NumberFormatter as IntlNumberFormatter;
use UAM\Twig\Extension\I18n\Formatter\NumberFormatter;

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
     * @dataProvider bytesProviderEn()
     */
    public function testBytesFilterEn($number, $format, $expected)
    {
        $locale = 'en';

        $this->assertEquals(
            $expected,
            $this->getFormatter()->formatBytes($number, $format, $locale)
        );
    }

    public function bytesProviderEn()
    {
        return array(
            //when format is B and b
            array(1048576, 'B', '1048576B'),
            array(1048576, 'b', '1048576B'),
            array(600, 'B', '600B'),

            //when format is k, kb, Kb, KB, kB
            array(9728, 'k', '9KB'),
            array(9728, 'kb', '9KB'),
            array(9728, 'Kb', '9KB'),
            array(9728, 'KB', '9KB'),
            array(9728, 'kB', '9KB'),

            //when format is m, M, mb, MB, mB
            array(12897484, 'm', '12MB'),
            array(12897484, 'M', '12MB'),
            array(12897484, 'mb', '12MB'),
            array(12897484, 'MB', '12MB'),
            array(12897484, 'mb', '12MB'),

            //when format is g, G, Gb, GB, gB
            array(16106127360, 'g', '15GB'),
            array(16106127360, 'G', '15GB'),
            array(16106127360, 'Gb', '15GB'),
            array(16106127360, 'GB', '15GB'),
            array(16106127360, 'gB', '15GB'),

            //when format is t, T, Tb, TB, tB
            array(7916483719987.2, 't', '7TB'),
            array(7916483719987.2, 'T', '7TB'),
            array(7916483719987.2, 'Tb', '7TB'),
            array(7916483719987.2, 'TB', '7TB'),
            array(7916483719987.2, 'tB', '7TB'),

            //when format is p, P, Pb, PB, pB
            array(9002199254340800, 'p', '7PB'),
            array(9002199254340800, 'P', '7PB'),
            array(9002199254340800, 'Pb', '7PB'),
            array(9002199254340800, 'PB', '7PB'),
            array(9002199254340800, 'pB', '7PB'),

            //when formatted bytes is less than 1 then automatically convert into nearest human readable
            array(2048, 'M', '2KB'),

            //when format is h then automatically convert into nearest human readable
            array(2048, 'h', '2KB'),
            array(2048, 'H', '2KB'),

            //when bytes is null
            array('', 'KB', '0B'),

            //when format is other than specified format then
            array(2048, 'A', '2KB'),

            //when bytes and format is null
            array('', '', '0B'),
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
