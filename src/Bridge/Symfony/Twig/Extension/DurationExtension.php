<?php

namespace UAM\Twig\Extension\I18n\Bridge\Symfony\Twig\Extension;

use DateTime;
use Symfony\Component\Translation\TranslatorInterface;
use Twig_Extension;
use Twig_SimpleFilter;

class DurationExtension extends Twig_Extension
{
    protected $sequence = array('y','m','d','h','i','s');

    protected $factor = array(
        'ym' => 12,
        'md' => 30,
        'dh' => 24,
        'hi' => 60,
        'is' => 60,
        'yd' => 365,
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

    public function durationFilter( $from, $to, $format='YYY-MMM-DDD-HHH-III-SSS')
    {
        $from_date = new DateTime($from);

        $to_date = new DateTime($to);

        $duration = $from_date->diff($to_date);

        $years = $duration->format('%y');
        $months = $duration->format('%m');
        $days = $duration->format('%d');
        $hours = $duration->format('%h');
        $minutes = $duration->format('%i');
        $seconds = $duration->format('%s');
        $result = '';

        $formats = explode('-',$format);

        for ($i = 0; $i < count($formats); $i++) {

            $value = $formats[$i];

            if (preg_match('/[yY]{1,3}/',$value,$matches) ) {

                if(ctype_lower($value) && $years == 0) {
                    continue;
                }

                $result .= $duration->format('%y');

                switch(strlen($value)) {
                    case 1:
                        $result .= 'y';
                        break;
                    case 2:
                        $result .= ' yrs';
                        break;
                    case 3:
                        $result .= ' years';
                        break;
                }
            }

            if (preg_match('/[mM]{1,3}/',$value,$matches) ) {

                if(ctype_lower($value) && $months == 0) {
                    continue;
                }

                $result .= ' '.$this->convertToLowerUnit($duration, ($i - 1) < 0 ? null: $formats[$i - 1] , $value);

                switch(strlen($value)) {
                    case 1:
                        $result .= 'm';
                        break;
                    case 2:
                        $result .= ' mos';
                        break;
                    case 3:
                        $result .= ' months';
                        break;
                }
            }

            if (preg_match('/[dD]{1,3}/',$value,$matches) ) {

                if(ctype_lower($value) && $days == 0) {
                    continue;
                }

                 $result .= ' '.$this->convertToLowerUnit($duration, ($i - 1) < 0 ? null: $formats[$i - 1] , $value);

                switch(strlen($value)) {
                    case 1:
                        $result .= 'd';
                        break;
                    case 2:
                        $result .= ' ds';
                        break;
                    case 3:
                        $result .= ' days';
                        break;
                }
            }

            if (preg_match('/[hH]{1,3}/',$value,$matches) ) {

                if(ctype_lower($value) && $hours == 0) {
                    continue;
                }

                 $result .= ' '.$this->convertToLowerUnit($duration, ($i - 1) < 0 ? null: $formats[$i - 1] , $value);

                switch(strlen($value)) {
                    case 1:
                        $result .= 'h';
                        break;
                    case 2:
                        $result .= ' hrs';
                        break;
                    case 3:
                        $result .= ' hours';
                        break;
                }
            }
            if (preg_match('/[iI]{1,3}/',$value,$matches) ) {

                if(ctype_lower($value) && $minutes == 0) {
                    continue;
                }

                $result .= ' '.$this->convertToLowerUnit($duration, ($i - 1) < 0 ? null: $formats[$i - 1] , $value);

                switch(strlen($value)) {
                    case 1:
                        $result .= 'i';
                        break;
                    case 2:
                        $result .= ' mins';
                        break;
                    case 3:
                        $result .= ' minutes';
                        break;
                }
            }
            if (preg_match('/[sS]{1,3}/',$value,$matches) ) {

                if(ctype_lower($value) && $seconds == 0) {
                    continue;
                }

                $result .= ' '.$seconds;

                switch(strlen($value)) {
                    case 1:
                        $result .= 's';
                        break;
                    case 2:
                        $result .= ' secs';
                        break;
                    case 3:
                        $result .= ' seconds';
                        break;
                }
            }
        }

        return $result;
    }

    protected function convertToLowerUnit($duration, $higher_format, $lower_format)
    {
        $upper_unit = $higher_format == null ? '' : strtolower(substr($higher_format, 0, 1));
        $lower_unit = strtolower(substr($lower_format, 0, 1));

        $u_index = $upper_unit == '' ? 0 : (int)array_keys($this->sequence, $upper_unit) + 1;
        $l_index = (int)array_keys($this->sequence, $lower_unit) - 1;

        for ($i = $u_index; $i <= $l_index; $i++) {
            $duration->{$lower_unit} += $duration->{$this->sequence[$i]} * $this->getFactor($this->sequence[$i], $lower_unit);
            $duration->{$this->sequence[$i]} = 0;
        }

        return $duration->{$lower_unit};
    }

    protected function getFactor($higher_unit, $lower_unit)
    {
        return $this->factor[$higher_unit.$lower_unit];
    }
}
