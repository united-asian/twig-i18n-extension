<?php

namespace  UAM\Twig\Extension\I18n\Test\Formatter;

use DateTime;
use IntlDateFormatter;

class DateTimeFormatterTest extends DateFormatterTest
{
    /**
     * @dataProvider dateProvider
     */
    public function testShortDate($date, $timezone, $locale)
    {
        $formatter = new IntlDateFormatter($locale, IntlDateFormatter::SHORT, IntlDateFormatter::SHORT, $timezone);

        $this->assertEquals(
            $formatter != null ? $formatter->format(new DateTime($date)) : 'ERR',
            $this->getFormatter()->formatDateTime($date, 'SHORT', 'SHORT', $timezone, $locale)
        );
    }

    /**
     * @dataProvider dateProvider
     */
    public function testMediumDate($date, $timezone, $locale)
    {
        $formatter = new IntlDateFormatter($locale, IntlDateFormatter::MEDIUM, IntlDateFormatter::MEDIUM, $timezone);

        $this->assertEquals(
            $formatter != null ? $formatter->format(new DateTime($date)) : 'ERR',
            $this->getFormatter()->formatDateTime($date, 'MEDIUM', 'MEDIUM', $timezone, $locale)
        );
    }

    /**
     * @dataProvider dateProvider
     */
    public function testLongDate($date, $timezone, $locale)
    {
        $formatter = new IntlDateFormatter($locale, IntlDateFormatter::LONG, IntlDateFormatter::LONG, $timezone);

        $this->assertEquals(
            $formatter != null ? $formatter->format(new DateTime($date)) : 'ERR',
            $this->getFormatter()->formatDateTime($date, 'LONG', 'LONG', $timezone, $locale)
        );
    }

    /**
     * @dataProvider dateProvider
     */
    public function testFullDate($date, $timezone, $locale)
    {
        $formatter = new IntlDateFormatter($locale, IntlDateFormatter::FULL, IntlDateFormatter::FULL, $timezone);

        $this->assertEquals(
            $formatter != null ? $formatter->format(new DateTime($date)) : 'ERR',
            $this->getFormatter()->formatDateTime($date, 'FULL', 'FULL', $timezone, $locale)
        );
    }

    /**
     * @dataProvider dateProvider
     */
    public function testConstant($date, $timezone, $locale)
    {
        foreach ($this->getConstants() as $constant => $format) {
            $this->assertEquals(
                $this->getFormatter()->formatDateTime($date, $constant, '  ', $timezone, $locale),
                $this->getDateTime($date, $timezone)->format($format)
            );
        }
    }
}
