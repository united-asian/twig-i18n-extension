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
    const ZERO = '0';

    private $translator;

    protected $bytes_units = array(
        'B' => 0,
        'K' => 1,
        'M' => 2,
        'G' => 3,
        'T' => 4,
        'P' => 5,
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

    public function formatBytes($bytes, $format = 'h', $locale = null)
    {
        $format = strtoupper($format);

        $locale = $this->getLocale($locale);

        if (!$bytes) {
            return static::ZERO.$this->trans('B', $locale);
        }

        if (!preg_match('/^(?:B|([KMGTP])B?)$/', $format, $matches)) {
            $format = $this->getAppropriateBytesFormat($bytes);
        } else {
            if (isset($matches[1])) {
                $format = $matches[1];
            }
        }

        $converted_value = $this->getConvertedBytesValue($bytes, $format);

        if ($converted_value < 1) {
            $format = $this->getAppropriateBytesFormat($bytes);
            $converted_value = $this->getConvertedBytesValue($bytes, $format);
        }

        return $converted_value.$this->trans($format, $locale);
    }

    protected function getAppropriateBytesFormat($bytes)
    {
        $pow = floor((log($bytes, 1024)));

        return array_search($pow, $this->bytes_units);
    }
    protected function getConvertedBytesValue($bytes, $format)
    {
        return floor($bytes / pow(1024, $this->bytes_units[$format]));
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
