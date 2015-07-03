<?php

namespace UAM\Twig\Extension\I18n;

use DateTime;
use Twig_Extension;
use Twig_SimpleFilter;
use Symfony\Component\Translation\TranslatorInterface;

class DurationExtension extends Twig_Extension
{
    protected $sequence = array('y', 'm', 'd', 'h', 'i', 's');

    protected $factor = array(
        'ym' => 12,
        'dh' => 24,
        'di' => 1440,
        'ds' => 86400,
        'hi' => 60,
        'hs' => 3600,
        'is' => 60,
    );

    private $translator;

    const DATE = 'duration.date';
    const RANGE = 'duration.range';

    public function __construct(TranslatorInterface $translator = null)
    {
        $this->translator = $translator;
    }

    public function getFilters()
    {
        return array(
            new Twig_SimpleFilter('duration', array($this, 'durationFilter'), array('is_safe' => array('html'))),
        );
    }

    public function getName()
    {
        return 'duration';
    }

    public function durationFilter($from, $to, $format='YYY-MMM-DDD-HHH-III-SSS')
    {
        $from_date = new DateTime($from);

        $to_date = new DateTime($to);

        $duration = $from_date->diff($to_date);

        $result = '';

        $formats = explode('-', $format);

        for ($i = 0; $i < count($formats); $i++) {
            $value = $formats[$i];

            $duration = $this->convertToLowerUnit($formats, $duration, ($i - 1) < 0 ? null: $formats[$i - 1], $value);

            if (preg_match('/[yY]{1,3}/', $value, $matches)) {
                if (ctype_lower($value) && $duration->format('%y') == 0) {
                    continue;
                }

                $result .= $duration->format('%y');

                switch (strlen($value)) {
                    case 1 :
                        $result .= 'y';
                        break;
                    case 2 :
                        $result .= ' yrs';
                        break;
                    case 3 :
                        $result .= ' years';
                        break;
                }
            }

            if (preg_match('/[mM]{1,3}/', $value, $matches)) {
                if (ctype_lower($value) && $duration->format('%m') == 0) {
                    continue;
                }

                $result .= ' '.$duration->format('%m');

                switch (strlen($value)) {
                    case 1 :
                        $result .= 'm';
                        break;
                    case 2 :
                        $result .= ' mos';
                        break;
                    case 3 :
                        $result .= ' months';
                        break;
                }
            }

            if (preg_match('/[dD]{1,3}/', $value, $matches)) {
                if (ctype_lower($value) && $duration->format('%d') == 0) {
                    continue;
                }

                $result .= ' '.$duration->format('%d');

                switch (strlen($value)) {
                    case 1 :
                        $result .= 'd';
                        break;
                    case 2 :
                        $result .= ' ds';
                        break;
                    case 3 :
                        $result .= ' days';
                        break;
                }
            }

            if (preg_match('/[hH]{1,3}/', $value, $matches)) {
                if (ctype_lower($value) && $duration->format('%h') == 0) {
                    continue;
                }

                $result .= ' '.$duration->format('%h');

                switch (strlen($value)) {
                    case 1 :
                        $result .= 'h';
                        break;
                    case 2 :
                        $result .= ' hrs';
                        break;
                    case 3 :
                        $result .= ' hours';
                        break;
                }
            }

            if (preg_match('/[iI]{1,3}/', $value, $matches)) {
                if (ctype_lower($value) && $duration->format('%i') == 0) {
                    continue;
                }

                $result .= ' '.$duration->format('%i');

                switch (strlen($value)) {
                    case 1 :
                        $result .= 'i';
                        break;
                    case 2 :
                        $result .= ' mins';
                        break;
                    case 3 :
                        $result .= ' minutes';
                        break;
                }
            }

            if (preg_match('/[sS]{1,3}/', $value, $matches)) {
                if (ctype_lower($value) && $duration->format('%s') == 0) {
                    continue;
                }

                $result .= ' '.$duration->format('%s');

                switch (strlen($value)) {
                    case 1 :
                        $result .= 's';
                        break;
                    case 2 :
                        $result .= ' secs';
                        break;
                    case 3 :
                        $result .= ' seconds';
                        break;
                }
            }
        }

        return $result;
    }

    //TODO convert Month to days.
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
}
