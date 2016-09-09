<?php

namespace UAM\Twig\Extension\I18n\Formatter;

use Exception;
use NumberFormatter as IntlNumberFormatter;

/**
 * Formats numeric values to localized number string.
 */
class NumberFormatter extends AbstractFormatter
{
    protected $storage_size_units = array(
        'b' => 0,
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

    public function formatBytes($bytes, $format, $locale)
    {
        $readable = 'h';
        $this->storage_size_units[$readable] = 'readable';
        $formats = array_keys($this->storage_size_units);

        if (!array_key_exists($format, $this->storage_size_units)) {
            throw new Exception(" you have to specify a legal Byte value or 'h' for automatic. Legal options are: h, ".implode(', ', $formats));
        }

        if ($bytes == null) {
            return '0'.$formats[0];
        }

        if ($format === null || $format == $readable) {
            $pow = floor(log($bytes) / log(1024));
            $format = array_search($pow, $this->storage_size_units);
        }

        $formatted_value = floor($bytes / pow(1024, ($this->storage_size_units[$format])));

        if ($formatted_value < 1) {
            $pow = floor(log($bytes) / log(1024));
            $format = array_search($pow, $this->storage_size_units);
            $formatted_value = floor($bytes / pow(1024, ($this->storage_size_units[$format])));
        }

        return $formatted_value.$format;
    }
}
