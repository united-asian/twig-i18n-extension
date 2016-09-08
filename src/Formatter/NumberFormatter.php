<?php

namespace UAM\Twig\Extension\I18n\Formatter;

use NumberFormatter as IntlNumberFormatter;

/**
 * Formats numeric values to localized number string.
 */
class NumberFormatter extends AbstractFormatter
{
    protected $units = array(
        'B' => 0,
        'Kb' => 1,
        'Mb' => 2,
        'Gb' => 3,
        'Tb' => 4,
        'Pb' => 5,
    );

    public function formatNumber($number, $locale = null)
    {
        $locale = $this->getLocale($locale);

        $formatter = new IntlNumberFormatter($locale, IntlNumberFormatter::DECIMAL);

        $formatter->setAttribute(IntlNumberFormatter::MIN_FRACTION_DIGITS, 0);

        $formatter->setAttribute(IntlNumberFormatter::MAX_FRACTION_DIGITS, 100);

        return $formatter->format($number);
    }

    public function formatInteger($number, $locale = null)
    {
        $locale = $this->getLocale($locale);

        $formatter = new IntlNumberFormatter($locale, IntlNumberFormatter::DECIMAL);

        $formatter->setAttribute(IntlNumberFormatter::MIN_FRACTION_DIGITS, 0);

        $formatter->setAttribute(IntlNumberFormatter::MAX_FRACTION_DIGITS, 0);

        return $formatter->format($number);
    }

    public function formatDecimal($number, $decimals = 2, $locale = null)
    {
        $decimals = max(0, $decimals);

        $locale = $this->getLocale($locale);

        $formatter = new IntlNumberFormatter($locale, IntlNumberFormatter::DECIMAL);

        $formatter->setAttribute(IntlNumberFormatter::MIN_FRACTION_DIGITS, $decimals);

        $formatter->setAttribute(IntlNumberFormatter::MAX_FRACTION_DIGITS, $decimals);

        return $formatter->format($number);
    }

    public function formatCurrency($amount, $currency = null, $locale = null)
    {
        $currency = $currency ?: IntlNumberFormatter::CURRENCY_CODE;

        $locale = $this->getLocale($locale);

        $formatter = new IntlNumberFormatter($locale, IntlNumberFormatter::CURRENCY);

        return $formatter->formatCurrency($amount, $currency);
    }

    public function formatPercent($number, $decimals = 2, $locale = null)
    {
        $decimals = max(0, $decimals);

        $locale = $this->getLocale($locale);

        $formatter = new IntlNumberFormatter($locale, IntlNumberFormatter::PERCENT);
        $formatter->setAttribute(IntlNumberFormatter::MIN_FRACTION_DIGITS, $decimals);
        $formatter->setAttribute(IntlNumberFormatter::MAX_FRACTION_DIGITS, $decimals);

        return $formatter->format($number);
    }

    public function formatBytes($bytes, $decimals, $unit)
    {
        if ($unit == null) {
            $pow = floor(log($bytes) / log(1024));
            $unit = array_search($pow, $this->units);
        }

        if (!array_key_exists($unit, $this->units)) {
            throw new Exception("unit is not supported");
        }

        if (!is_numeric($decimals) || $decimals < 0) {
            $decimals = 2;
        }

        return round($bytes / pow(1024, floor($this->units[$unit])), $decimals) . $unit;
    }
}
