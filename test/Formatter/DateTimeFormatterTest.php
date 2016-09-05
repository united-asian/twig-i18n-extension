<?php

namespace  UAM\Twig\Extension\I18n\test\Formatter;

use DateTime;
use IntlDateFormatter;
use UAM\Twig\Extension\I18n\Formatter\DateTimeFormatter;

class DateTimeFormatterTest extends DateFormatterTest
{
    /**
     * @dataProvider dateProvider
     */
    public function testShortDate($date, $timezone, $locale)
    {
        $formatter = new IntlDateFormatter($locale, IntlDateFormatter::SHORT, IntlDateFormatter::SHORT, $timezone);

        if ($formatter == null) {
            $formatted_value = DateTimeFormatter::ERROR;
        } else {
            $formatted_value = $formatter->format(new DateTime($date));
        }

        $this->assertEquals(
            $formatted_value,
            $this->getFormatter()->formatDateTime($date, 'SHORT', 'SHORT', $timezone, $locale)
        );
    }

    /**
     * @dataProvider dateProvider
     */
    public function testMediumDate($date, $timezone, $locale)
    {
        $formatter = new IntlDateFormatter($locale, IntlDateFormatter::MEDIUM, IntlDateFormatter::MEDIUM, $timezone);
        if ($formatter == null) {
            $formatted_value = DateTimeFormatter::ERROR;
        } else {
            $formatted_value = $formatter->format(new DateTime($date));
        }

        $this->assertEquals(
            $formatted_value,
            $this->getFormatter()->formatDateTime($date, 'MEDIUM', 'MEDIUM', $timezone, $locale)
        );
    }

    /**
     * @dataProvider dateProvider
     */
    public function testLongDate($date, $timezone, $locale)
    {
        $formatter = new IntlDateFormatter($locale, IntlDateFormatter::LONG, IntlDateFormatter::LONG, $timezone);

        if ($formatter == null) {
            $formatted_value = DateTimeFormatter::ERROR;
        } else {
            $formatted_value = $formatter->format(new DateTime($date));
        }

        $this->assertEquals(
            $formatted_value,
            $this->getFormatter()->formatDateTime($date, 'LONG', 'LONG', $timezone, $locale)
        );
    }

    /**
     * @dataProvider dateProvider
     */
    public function testFullDate($date, $timezone, $locale)
    {
        $formatter = new IntlDateFormatter($locale, IntlDateFormatter::FULL, IntlDateFormatter::FULL, $timezone);

        if ($formatter == null) {
            $formatted_value = DateTimeFormatter::ERROR;
        } else {
            $formatted_value = $formatter->format(new DateTime($date));
        }

        $this->assertEquals(
            $formatted_value,
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
