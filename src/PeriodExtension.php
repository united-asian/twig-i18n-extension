<?php

namespace UAM\Twig\Extension\I18n;

use DateTime;
use IntlDateFormatter;
use Locale;
use Twig_Extension;
use Twig_SimpleFilter;
use Symfony\Component\Translation\TranslatorInterface;

class PeriodExtension extends Twig_Extension
{
    private $translator;

    const DATE = 'period.date';
    const RANGE = 'period.range';

    public function __construct(TranslatorInterface $translator = null)
    {
        $this->translator = $translator;
    }

    public function getFilters()
    {
        return array(
            new Twig_SimpleFilter('period', array($this, 'periodFilter'), array('is_safe' => array('html'))),
        );
    }

    public function getName()
    {
        return 'period';
    }

    public function periodFilter($from, $to, $locale = null, $day_format = 'd', $month_format = 'MMMM', $year_format = 'yyyy')
    {
        $from_date = new DateTime($from);
        $to_date = new DateTime($to);

        $locale = $locale !== null ? $locale : Locale::getDefault();

        $from_formatter  = new IntlDateFormatter($locale, null, null);
        $to_formatter = new IntlDateFormatter($locale, null, null);

        if ($from_date->format('Y-m-d') == $to_date->format('Y-m-d')) {
            $to_formatter->setPattern(implode(' ', array($day_format, $month_format, $year_format)));
            $to = $to_formatter->format($to_date);

            return $this->translator->trans(self::DATE, array('%to%' => $to), 'uam-i18n', $locale);
        }

        if ($from_date->format('Y-m') == $to_date->format('Y-m')) {
            $from_formatter->setPattern(implode(' ', array($day_format)));
            $from = $from_formatter->format($from_date);

            return $this->getFormattedValue($from, $to, $locale, $day_format, $month_format, $year_format);
        }

        if ($from_date->format('Y') == $to_date->format('Y')) {
            $from_formatter->setPattern(implode(' ', array($day_format, $month_format)));
            $from = $from_formatter->format($from_date);

            return $this->getFormattedValue($from, $to, $locale, $day_format, $month_format, $year_format);
        }

        $from_formatter->setPattern(implode(' ', array($day_format, $month_format, $year_format)));
        $from = $from_formatter->format($from_date);

        return $this->getFormattedValue($from, $to, $locale, $day_format, $month_format, $year_format);
    }

    private function getFormattedValue($from, $to, $locale, $day_format, $month_format, $year_format)
    {
        $to_date = new DateTime($to);
        $to_formatter = new IntlDateFormatter($locale, null, null);
        $to_formatter->setPattern(implode(' ', array($day_format, $month_format, $year_format)));
        $to = $to_formatter->format($to_date);

        return $this->translator->trans(self::RANGE, array('%from%' => $from, '%to%' =>  $to), 'uam-i18n', $locale);
    }
}
