<?php

namespace  UAM\Twig\Extension\I18n\test\Formatter;

use DateTime;
use DateTimeZone;
use IntlDateFormatter;
use Faker\Factory;
use UAM\Twig\Extension\I18n\Formatter\DateTimeFormatter;

class DateTimeFormatterTest extends DateFormatterTest
{
    /**
     * @dataProvider dateProvider
     */
    public function testShortDate($date, $timezone, $locale)
    {
        $formatter = new IntlDateFormatter($locale, IntlDateFormatter::SHORT, IntlDateFormatter::SHORT, $timezone);

        if($formatter == null) {
            $this->markTestSkipped('Err');
        } else {
            $this->assertEquals(
                $this->getFormatter()->formatDateTime($date, 'SHORT', 'SHORT', $timezone, $locale),
                $formatter->format(new DateTime($date))
            );
        }
    }

    /**
     * @dataProvider dateProvider
     */
    public function testMediumDate($date, $timezone, $locale)
    {
        $formatter = new IntlDateFormatter($locale, IntlDateFormatter::MEDIUM, IntlDateFormatter::MEDIUM, $timezone);

        if($formatter == null) {
            $this->markTestSkipped('Err');
        } else {
            $this->assertEquals(
                $this->getFormatter()->formatDateTime($date, 'MEDIUM', 'MEDIUM', $timezone, $locale),
                $formatter->format(new DateTime($date))
            );
        }
    }

    /**
     * @dataProvider dateProvider
     */
    public function testLongDate($date, $timezone, $locale)
    {
        $formatter = new IntlDateFormatter($locale, IntlDateFormatter::LONG, IntlDateFormatter::LONG, $timezone);

        if($formatter == null) {
            $this->markTestSkipped('Err');
        } else {
            $this->assertEquals(
                $this->getFormatter()->formatDateTime($date, 'LONG', 'LONG', $timezone, $locale),
                $formatter->format(new DateTime($date))
            );
        }
    }

    /**
     * @dataProvider dateProvider
     */
    public function testFullDate($date, $timezone, $locale)
    {
        $formatter = new IntlDateFormatter($locale, IntlDateFormatter::FULL, IntlDateFormatter::FULL, $timezone);

        if($formatter == null) {
            $this->markTestSkipped('Err');
        } else {
            $this->assertEquals(
                $this->getFormatter()->formatDateTime($date, 'FULL', 'FULL', $timezone, $locale),
                $formatter->format(new DateTime($date))
            );
        }
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
