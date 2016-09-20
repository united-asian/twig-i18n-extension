<?php

namespace UAM\Twig\Extension\I18n;

use DateInterval;
use DateTime;
use Locale;
use Twig_Extension;
use Twig_SimpleFilter;
use Symfony\Component\Translation\MessageSelector;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\Loader\JsonFileLoader;

class DurationExtension extends Twig_Extension
{
    const CATALOGUE = 'uam-18n';
    const YEAR = 'y';
    const MONTH = 'm';
    const DAY = 'd';
    const HOUR = 'h';
    const MINUTE = 'i';
    const SECOND = 's';
    const ROUND_VALUE_MONTH = 6;
    const ROUND_VALUE_DAY = 15;
    const ROUND_VALUE_HOUR = 12;
    const ROUND_VALUE_MINUTE = 30;
    const ROUND_VALUE_SECOND = 30;

    protected $sequence = array('y', 'm', 'd', 'h', 'i', 's');

    protected $factor = array(
        'ym' => 12,
        'md' => 30,
        'dh' => 24,
        'di' => 1440,
        'ds' => 86400,
        'hi' => 60,
        'hs' => 3600,
        'is' => 60,
    );

    protected static $units = array(
        'y' => 'duration.year.short',
        'yy' => 'duration.year.medium',
        'yyy' => 'duration.year.full',
        'm' => 'duration.month.short',
        'mm' => 'duration.month.medium',
        'mmm' => 'duration.month.full',
        'd' => 'duration.day.short',
        'dd' => 'duration.day.medium',
        'ddd' => 'duration.day.full',
        'h' => 'duration.hour.short',
        'hh' => 'duration.hour.medium',
        'hhh' => 'duration.hour.full',
        'i' => 'duration.minute.short',
        'ii' => 'duration.minute.medium',
        'iii' => 'duration.minute.full',
        's' => 'duration.second.short',
        'ss' => 'duration.second.medium',
        'sss' => 'duration.second.full',
    );

    private $translator;

    public function getFilters()
    {
        return array(
            new Twig_SimpleFilter('duration', array($this, 'duration'), array('is_safe' => array('html'))),
        );
    }

    public function getName()
    {
        return 'duration';
    }

    public function duration($from, $to, $format='YYY-MMM-DDD-HHH-III-SSS', $locale = null)
    {
        $interval = self::getDateInterval($from, $to, $format);

        $locale = $locale !== null ? $locale : Locale::getDefault();

        $duration = $interval;

        $result = '';

        $formats = explode('-', strtolower($format));

        $result = array();

        $regexes = array(
            '/^[y]{1,3}/' => '%y',
            '/^[m]{1,3}/' => '%m',
            '/^[d]{1,3}/' => '%d',
            '/^[h]{1,3}/' => '%h',
            '/^[i]{1,3}/' => '%i',
            '/^[sS]{1,3}/' => '%s',
        );

        $n = count($formats);

        foreach ($formats as $i => $format) {
            $duration = $this->convertToLowerUnit($formats, $duration, ($i - 1) < 0 ? null: $formats[$i - 1], $format);

            foreach ($regexes as $regex => $date_format) {
                if (preg_match($regex, $format, $matches)) {
                    $value = $duration->format($date_format);

                    if (0 == $value) {
//                        continue;
                    }

                    $unit = $this->getUnit($format);

                    $result[] = $this->trans($unit, $value, $locale);

                    break;
                }
            }
        }

        return implode(' ', $result);
    }

    public function getDateInterval($from, $to, $format)
    {
        $interval = $this->getRawDateInterval($from, $to);

        $formats = explode('-', strtolower($format));

        foreach ($formats as $i => $format) {
            $interval = $this->convertToLowerUnit($formats, $interval, ($i - 1) < 0 ? null : $formats[$i - 1], $format);
        }

        $last_unit = end($formats);

        switch ($last_unit) {
            case self::YEAR :
                if ($interval->m > self::ROUND_VALUE_MONTH) {
                    $interval->y++;
                }

                break;

            case self::MONTH :
                if ($interval->d > self::ROUND_VALUE_DAY) {
                    $interval->m++;
                }

                break;

            case self::DAY :
                if ($interval->h > self::ROUND_VALUE_HOUR) {
                    $interval->d++;
                }

                break;

            case self::HOUR :
                if ($interval->i > self::ROUND_VALUE_MINUTE) {
                    $interval->h++;
                }

                break;

            case self::MINUTE :
                if ($interval->s > self::ROUND_VALUE_SECOND) {
                    $interval->i++;
                }
        }

        return $interval;
    }

    public function getRawDateInterval($from, $to)
    {
        if (strtotime($from) > strtotime($to)) {
            $temp_date = $from;
            $from = $to;
            $to = $temp_date;
        }

        $parsed = date_parse($from);

        if (!is_int($parsed['hour'])) {
            $from .= ' 00:00:00';
        }

        $start_date = new DateTime($from);

        $parsed = date_parse($to);

        if (!is_int($parsed['hour'])) {
            $to .= ' 23:59:59';
            $end_date = new DateTime($to);
            $end_date->add(new DateInterval('PT1S'));
        } else {
            $end_date = new DateTime($to);
        }

        $interval = $start_date->diff($end_date);

        return $interval;
    }

    protected function convertToLowerUnit($formats, $duration, $higher_format, $lower_format)
    {
        $upper_unit = $higher_format == null ? '' : strtolower(substr($higher_format, 0, 1));
        $lower_unit = strtolower(substr($lower_format, 0, 1));

        if ($lower_unit == 'y') {
            return $duration;
        }

        $u_index = $upper_unit == '' ? 0 : ((int)array_search($upper_unit, $this->sequence) + 1);
        $l_index = (int)array_search($lower_unit, $this->sequence) - 1;

        if ($higher_format == null && $l_index >= 1) {
            $total_days = $duration->format('%a');

            $duration->y = 0;
            $duration->m = 0;
            $duration->d = $total_days;
        }

        for ($i = $u_index; $i <= $l_index; $i++) {
            $duration->{$lower_unit} += $duration->{$this->sequence[$i]} * $this->getFactor($duration, $formats, $this->sequence[$i], $lower_unit);
            $duration->{$this->sequence[$i]} = 0;
        }

        return $duration;
    }

    protected function getFactor($duration, $formats, $higher_unit, $lower_unit)
    {
        $factor = 1;

        if (array_key_exists($higher_unit.$lower_unit, $this->factor)) {
            $factor = $this->factor[$higher_unit.$lower_unit];
        }

        return $factor;
    }

    protected function getUnit($format)
    {
        return static::$units[$format];
    }

    protected function trans($message, $value, $locale)
    {
        $translator = $this->getTranslator();

        return $translator->transChoice($message, $value, array('%count%' => $value), static::CATALOGUE, $locale);
    }

    protected function getTranslator()
    {
        if (!$this->translator) {
            $this->translator = new Translator('en', new MessageSelector());

            $this->translator->addLoader('json', new JsonFileLoader());

            $this->translator->addResource(
                'json',
                dirname(__FILE__).'/uam-i18n.en.json',
                'en',
                static::CATALOGUE
            );

            $this->translator->addResource(
                'json',
                dirname(__FILE__).'/uam-i18n.fr.json',
                'fr',
                static::CATALOGUE
            );
        }

        return $this->translator;
    }
}
