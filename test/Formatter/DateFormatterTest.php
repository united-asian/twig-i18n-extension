<?php

namespace  UAM\Twig\Extension\I18n\test\Formatter;

use DateTime;
use DateTimeZone;
use IntlDateFormatter;
use Faker\Factory;
use UAM\Twig\Extension\I18n\Formatter\DateTimeFormatter;

class DateFormatterTest extends AbstractFormatterTestCase
{
    /**
     * @dataProvider dateProvider
     */
    public function testShortDate($date, $timezone, $locale)
    {
        $formatter = new IntlDateFormatter($locale, IntlDateFormatter::SHORT, IntlDateFormatter::NONE, $timezone);

        if ($formatter == null) {
            $formatted_value = DateTimeFormatter::ERROR;
        } else {
            $formatted_value = $formatter->format($this->getDateTime($date, $timezone));
        }

        $this->assertEquals(
            $formatted_value,
            $this->getFormatter()->formatDate($date, 'SHORT', $timezone, $locale)
        );
    }

    /**
     * @dataProvider dateProvider
     */
    public function testMediumDate($date, $timezone, $locale)
    {
        $formatter = new IntlDateFormatter($locale, IntlDateFormatter::MEDIUM, IntlDateFormatter::NONE, $timezone);

        if ($formatter == null) {
            $formatted_value = DateTimeFormatter::ERROR;
        } else {
            $formatted_value = $formatter->format($this->getDateTime($date, $timezone));
        }

        $this->assertEquals(
            $formatted_value,
            $this->getFormatter()->formatDate($date, 'MEDIUM', $timezone, $locale)
        );
    }

    /**
     * @dataProvider dateProvider
     */
    public function testLongDate($date, $timezone, $locale)
    {
        $formatter = new IntlDateFormatter($locale, IntlDateFormatter::LONG, IntlDateFormatter::NONE, $timezone);

        if ($formatter == null) {
            $formatted_value = DateTimeFormatter::ERROR;
        } else {
            $formatted_value = $formatter->format($this->getDateTime($date, $timezone));
        }

        $this->assertEquals(
            $formatted_value,
            $this->getFormatter()->formatDate($date, 'LONG', $timezone, $locale)
        );
    }

    /**
     * @dataProvider dateProvider
     */
    public function testFullDate($date, $timezone, $locale)
    {
        $formatter = new IntlDateFormatter($locale, IntlDateFormatter::FULL, IntlDateFormatter::NONE, $timezone);

        if ($formatter == null) {
            $formatted_value = DateTimeFormatter::ERROR;
        } else {
            $formatted_value = $formatter->format($this->getDateTime($date, $timezone));
        }

        $this->assertEquals(
            $formatted_value,
            $this->getFormatter()->formatDate($date, 'FULL', $timezone, $locale)
        );
    }

    /**
     * @dataProvider dateProvider
     */
    public function testConstant($date, $timezone, $locale)
    {
        foreach ($this->getConstants() as $constant => $format) {
            $this->assertEquals(
                $this->getFormatter()->formatDate($date, $constant, $timezone, $locale),
                $this->getDateTime($date, $timezone)->format($format)
            );
        }
    }

    public function dateProvider()
    {
        $faker = Factory::create();

        $dates = array();

        for ($i = 0; $i < 10; $i++) {
            $dates[] = array(
                $faker->date('Y-m-d'),
                $faker->timezone(),
                $faker->locale()
            );
        }

        return $dates;
    }

    protected function setupFormatter()
    {
        return new DateTimeFormatter();
    }

    protected function getConstants()
    {
        return array(
            'ATOM'    => DateTime::ATOM,
            'COOKIE'  => DateTime::COOKIE,
            'ISO8601' => DateTime::ISO8601,
            'RFC822'  => DateTime::RFC822,
            'RFC850'  => DateTime::RFC850,
            'RFC1036' => DateTime::RFC1036,
            'RFC1123' => DateTime::RFC1123,
            'RFC2822' => DateTime::RFC2822,
            'RFC3339' => DateTime::RFC3339,
            'RSS'     => DateTime::RSS,
            'W3C'     => DateTime::W3C,
            'R'       => DateTime::RFC2822, // date() => r
            'C'       => DateTime::ISO8601, // date() => C
            'U'       => 'U', // date() => U
        );
    }

    protected function getDateTime($date, $timezone)
    {
        $datetime = new DateTime($date);

        $datetime->setTimezone(new DateTimeZone($timezone));

        return $datetime;
    }
}
