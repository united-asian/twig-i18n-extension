<?php

namespace UAM\Twig\Extension\I18n\Formatter;

use NumberFormatter as IntlNumberFormatter;
use Symfony\Component\Translation\Loader\JsonFileLoader;
use Symfony\Component\Translation\MessageSelector;
use Symfony\Component\Translation\Translator;

/**
 * Formats numeric values to localized number string.
 */
class NumberFormatter extends AbstractFormatter
{
    const CATALOGUE = 'uam-18n';

    protected $units;
    private $translator;

    protected $bytes_units = array(
        'B' => 'bytes_unit.bytes',
        'K' => 'bytes_unit.kilobytes',
        'M' => 'bytes_unit.megabytes',
        'G' => 'bytes_unit.gigabytes',
        'T' => 'bytes_unit.terabytes',
        'P' => 'bytes_unit.petabytes',
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

    public function formatBytes($bytes, $format, $locale = null)
    {
        $format = strtoupper($format);

        $locale = $this->getLocale($locale);

        $this->units = array_keys($this->bytes_units);

        if (!preg_match('/^(?:B|([KMGTP])B?)$/', $format, $matches)) {
            $format = 'h';
        } else {
            if (isset($matches[1])) {
                $format = $matches[1];
            }
        }

        if (!$bytes) {
            return '0'.$this->trans('B', $locale);
        }

        $index = $this->getIndex($bytes, $format);
        $formatted_value = $this->getFormattedValue($bytes, $index);

        if ($formatted_value < 1) {
            $format = 'h';
            $index = $this->getIndex($bytes, $format);
            $formatted_value = $this->getFormattedValue($bytes, $index);
        }

        return $formatted_value.$this->getFormat($index, $locale);
    }

    protected function getIndex($bytes, $format)
    {
        if ($format == 'h') {
            $index = floor((log($bytes, 1024)));
        } else {
            $index = array_search($format, $this->units);
        }

        return $index;
    }

    protected function getFormat($index, $locale)
    {
        $format = $this->units[$index];
        $bytes_unit = $this->bytes_units[$format];

        return $this->trans($bytes_unit, $locale);
    }

    protected function getFormattedValue($bytes, $index)
    {
        return floor($bytes / pow(1024, $index));
    }

    protected function trans($format, $locale)
    {
        $translator = $this->getTranslator();

        return $translator->trans($format, array(), static::CATALOGUE, $locale);
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
