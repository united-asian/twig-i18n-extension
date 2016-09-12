<?php

namespace UAM\Twig\Extension\I18n\Formatter;

use Exception;
use NumberFormatter as IntlNumberFormatter;

/**
 * Formats numeric values to localized number string.
 */
class NumberFormatter extends AbstractFormatter
{
    protected $bytes_units = array(
        'b' => 0,
        'k' => 1,
        'm' => 2,
        'g' => 3,
        't' => 4,
        'p' => 5,
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

    public function formatBytes($bytes, $format = null, $locale)
    {
        $format = strtolower($format);

        if (!preg_match('/^(?:b|([kmgtp])b?)$/', $format, $matches)) {
            $format = 'h';
        } else {
            if (isset($matches[1])) {
                $format = $matches[1];
            }
        }
        var_dump($format);



        if (!$bytes) {
            return '0b';
        }

        if ($format == 'h') {
            $format = $this->getFormat($bytes);
        }

        $formatted_value = $this->formattedValue($bytes, $format);

        if ($formatted_value < 1) {
            $format = $this->getFormat($bytes);
            $formatted_value = $this->formattedValue($bytes, $format);
        }

        return $formatted_value . $format;
    }

    protected function getFormat($bytes)
    {
        $pow = floor(log($bytes) / log(1024));

        return array_search($pow, $this->bytes_units);
    }

    protected function formattedValue($bytes, $format)
    {
        return floor($bytes / pow(1024, ($this->bytes_units[$format])));
    }
}
