<?php

namespace UAM\Twig\Extension\I18n\Formatter;

use NumberFormatter as BaseNumberFormatter;

/**
 * Formats numeric values to localized number string.
 */
class NumberFormatter extends AbstractFormatter
{
    public function formatNumber($number, $locale = null)
    {
        $locale = $this->getLocale($locale);
        $formatter = new BaseNumberFormatter($locale, BaseNumberFormatter::DECIMAL);
        $formatter->setAttribute(BaseNumberFormatter::MIN_FRACTION_DIGITS, 0);
        $formatter->setAttribute(BaseNumberFormatter::MAX_FRACTION_DIGITS, 100);

        return $formatter->format($number);
    }

    public function formatInteger($integer, $locale = null)
    {
        $locale = $this->getLocale($locale);
        $formatter = new BaseNumberFormatter($locale, BaseNumberFormatter::DECIMAL);
        $formatter->setAttribute(BaseNumberFormatter::MIN_FRACTION_DIGITS, 0);
        $formatter->setAttribute(BaseNumberFormatter::MAX_FRACTION_DIGITS, 0);

        return $formatter->format($integer);
    }

    public function formatDecimal($decimal, $round = null, $locale = null)
    {
        // TODO: if ($decimal < -1) $decimal = '<span class="decimal-negative">'.$decimal.'</span>';
        $round = $round ?: 2;
        $locale = $this->getLocale($locale);
        $formatter = new BaseNumberFormatter($locale, BaseNumberFormatter::DECIMAL);
        $formatter->setAttribute(BaseNumberFormatter::MIN_FRACTION_DIGITS, $round);
        $formatter->setAttribute(BaseNumberFormatter::MAX_FRACTION_DIGITS, $round);

        return $formatter->format($decimal);
    }

    public function formatCurrency($currency, $type = null, $locale = null)
    {
        $type = $type ?: 'EUR';
        $locale = $this->getLocale($locale);
        $formatter = new BaseNumberFormatter($locale, BaseNumberFormatter::CURRENCY);

        return $formatter->formatCurrency(round($currency, 2), $type);
    }

    public function formatPercent($percent, $round = 2, $base = null, $locale = null)
    {
        $round = $round ?: 2;
        $locale = $this->getLocale($locale);
        $formatter = new BaseNumberFormatter($locale, BaseNumberFormatter::PERCENT);
        $formatter->setAttribute(BaseNumberFormatter::MIN_FRACTION_DIGITS, $round);
        $formatter->setAttribute(BaseNumberFormatter::MAX_FRACTION_DIGITS, $round);

        return $formatter->format($percent);
    }
}
