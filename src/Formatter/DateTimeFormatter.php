<?php

namespace UAM\Twig\Extension\I18n\Formatter;

use DateTime;
use DateTimeZone;
use Exception;
use IntlDateFormatter;
use UAM\Twig\Extension\I18n\Formatter\AbstractFormatter;

/**
 * Formats DateTime objects or datetime-formated strings to localized
 * datetime string.
 */
class DateTimeFormatter extends AbstractFormatter
{
    const ERROR = 'ERR';

    protected $datetimeFormats = array(
        'NONE'   => IntlDateFormatter::NONE,
        'SHORT'  => IntlDateFormatter::SHORT,
        'MEDIUM' => IntlDateFormatter::MEDIUM,
        'LONG'   => IntlDateFormatter::LONG,
        'FULL'   => IntlDateFormatter::FULL,
    );

    protected $constants = array(
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

    public function formatDatetime($datetime, $formatDate = null, $formatTime = null, $timezone = null, $locale = null)
    {
        $datetime   = $this->getDateTime($datetime, $timezone);
        $locale     = $this->getLocale($locale);
        $format     = trim($formatDate.' '.$formatTime);

        if ($this->isConstant($format)) {
            return $datetime->format($this->getConstant($format));
        }

        if ($this->isPattern($formatDate) || $this->isPattern($formatTime)) {
            $formatter  = IntlDateFormatter::create($locale, null, null, $datetime->getTimezone()->getName());
            $formatter->setPattern($this->getFormat($format));
        } else {
            $formatDate = $this->getDateTimeFormat($formatDate, 'FULL');
            $formatTime = $this->getDateTimeFormat($formatTime, 'SHORT');

            try {
                $formatter  = IntlDateFormatter::create($locale, $formatDate, $formatTime, $datetime->getTimezone()->getName());
            } catch (Exception $e) {
                $formatter = null;
            }
        }

        if ($formatter == null) {
            return DateTimeFormatter::ERROR;
        }

        return $formatter->format($this->sanitizeDateForIntl($datetime));
    }

    public function formatDate($date, $format = null, $timezone = null, $locale = null)
    {
        $date       = $this->getDateTime($date, $timezone);
        $locale     = $this->getLocale($locale);

        if ($this->isConstant($format)) {
            return $date->format($this->getConstant($format));
        }

        if ($this->isPattern($format)) {
            $formatter  = IntlDateFormatter::create($locale, null, null, $date->getTimezone()->getName());
            $formatter->setPattern($this->getFormat($format));
        } else {
            $format     = $this->getDateTimeFormat($format, 'FULL');

            try {
                $formatter  = IntlDateFormatter::create($locale, $format, IntlDateFormatter::NONE, $date->getTimezone()->getName());
            } catch (Exception $e) {
                $formatter = null;
            }
        }

        if ($formatter == null) {
            return DateTimeFormatter::ERROR;
        }

        return $formatter->format($this->sanitizeDateForIntl($date));
    }

    public function formatDateRange(array $dates, $formatDay = null, $formatMonth = null, $formatYear = null, $timezone = null, $locale = null)
    {
        $start = $this->getDateTime($dates[0], $timezone);
        $end = $this->getDateTime($dates[1], $timezone);

        $locale = $this->getLocale($locale);

        $formatter  = IntlDateFormatter::create($locale, null, null, $start->getTimezone()->getName());

        if ($start->format('Y-m-d') == $end->format('Y-m-d')) {
            $formatter->setPattern(implode(' ', array($formatDay, $formatMonth, $formatYear)));

            return $formatter->format($start);
        }

        if ($start->format('Y-m') == $end->format('Y-m')) {
            $formatter->setPattern($formatMonth.($formatYear ? ' '.$formatYear : ''));

            $month = $formatter->format($start);

            $formatter->setPattern($formatDay);

            return sprintf(
                '%s - %s %s',
                $formatter->format($start),
                $formatter->format($end),
                $month
            );
        }

        if ($start->format('Y') == $end->format('Y')) {
            $formatter->setPattern($formatYear);

            $year = $formatter->format($start);

            $formatter->setPattern(implode(' ', array($formatDay, $formatMonth)));

            return sprintf(
                '%s - %s %s',
                $formatter->format($start),
                $formatter->format($end),
                $year
            );
        }

        $formatter->setPattern(implode(' ', array($formatDay, $formatMonth, $formatYear)));

        return sprintf(
            '%s - %s',
            $formatter->format($start),
            $formatter->format($end)
        );
    }

    protected function getDateTime($date, $timezone = null)
    {
        if (!$date instanceof DateTime) {
            if (ctype_digit((string) $date)) {
                $date = new DateTime('@'.$date);
            } else {
                $date = new DateTime($date);
            }
        }

        if ($timezone !== null) {
            if (!$timezone instanceof DateTimeZone) {
                $timezone = new DateTimeZone($timezone);
            }

            $date->setTimezone($timezone);
        } else {
            $date->setTimezone(new DateTimeZone(date_default_timezone_get()));
        }

        return $date;
    }

    protected function getDateTimeFormat($format, $default = null)
    {
        if (isset($this->datetimeFormats[strtoupper($format)])) {
            return $this->datetimeFormats[strtoupper($format)];
        }

        if (empty($format) && !empty($default)) {
            return $this->datetimeFormats[strtoupper($default)];
        }

        return IntlDateFormatter::SHORT;
    }

    protected function isPattern($format)
    {
        return !empty($format) && !isset($this->datetimeFormats[strtoupper($format)]);
    }

    protected function isConstant($format)
    {
        return isset($this->constants[strtoupper($format)]);
    }

    protected function getConstant($constant)
    {
        if ($this->isConstant($constant)) {
            return $this->constants[strtoupper($constant)];
        }

        return;
    }

    protected function getFormat($format)
    {
        if (isset($this->dateToDatetimeFormat[strtoupper($format)])) {
            return $this->dateToDatetimeFormat[strtoupper($format)];
        }

        return $format;
    }

    /**
     * IntlDateFormatter::format() accepts timestamps in seconds and since PHP 5.3.4 DateTime objects.
     *
     * This call ensures, that the method is fed the right format
     *
     * @author Markus Tacker <m@coderbyheart.de>
     *
     * @param string|\DateTime $date
     *
     * @return string|\DateTime
     */
    protected function sanitizeDateForIntl($date)
    {
        if (!($date instanceof DateTime)) {
            return $date;
        }
        if (version_compare(phpversion(), '5.3.4', '<')) {
            return $date->getTimestamp();
        }

        return $date;
    }

    public function formatTime($time, $format = null, $timezone = null, $locale = null)
    {
        $time       = $this->getDateTime($time, $timezone);
        $locale     = $this->getLocale($locale);

        if ($this->isConstant($format)) {
            return $time->format($this->getConstant($format));
        }

        if ($this->isPattern($format)) {
            $formatter  = IntlDateFormatter::create($locale, null, null, $time->getTimezone()->getName());
            $formatter->setPattern($this->getFormat($format));
        } else {
            $format     = $this->getDateTimeFormat($format, 'SHORT');
            $formatter  = IntlDateFormatter::create($locale, IntlDateFormatter::NONE, $format, $time->getTimezone()->getName());
        }

        return $formatter->format($this->sanitizeDateForIntl($time));
    }
}
