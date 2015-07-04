<?php

namespace UAM\Twig\Extension\I18n;

use Twig_Extension;
use Twig_SimpleFilter;
use UAM\Twig\Extension\I18n\Formatter\CountryFormatter;
use UAM\Twig\Extension\I18n\Formatter\DateTimeFormatter;
use UAM\Twig\Extension\I18n\Formatter\LanguageFormatter;
use UAM\Twig\Extension\I18n\Formatter\NumberFormatter;

/**
 * Twig extension as helper to localize data.
 */
class I18nExtension extends Twig_Extension
{
    protected $date_formatter;
    protected $number_formatter;
    protected $country_formatter;
    protected $language_formatter;

    public function getFilters()
    {
        return array(
            new Twig_SimpleFilter('date', array($this, 'formatDate'), array('is_safe' => array('html'))),
            new Twig_SimpleFilter('datetime', array($this, 'formatDatetime'), array('is_safe' => array('html'))),
            new Twig_SimpleFilter('time', array($this, 'formatTime'), array('is_safe' => array('html'))),
            new Twig_SimpleFilter('daterange', array($this, 'formatDateRange'), array('is_safe' => array('html'))),
            new Twig_SimpleFilter('number', array($this, 'formatNumber'), array('is_safe' => array('html'))),
            new Twig_SimpleFilter('integer', array($this, 'formatInteger'), array('is_safe' => array('html'))),
            new Twig_SimpleFilter('decimal', array($this, 'formatDecimal'), array('is_safe' => array('html'))),
            new Twig_SimpleFilter('percent', array($this, 'formatPercent'), array('is_safe' => array('html'))),
            new Twig_SimpleFilter('currency', array($this, 'formatCurrency'), array('is_safe' => array('html'))),
            new Twig_SimpleFilter('country', array($this, 'formatCountry'), array('is_safe' => array('html'))),
            new Twig_SimpleFilter('language', array($this, 'formatLanguage'), array('is_safe' => array('html'))),
        );
    }

    public function formatDatetime($datetime, $formatDate = null, $formatTime = null, $timezone = null, $locale = null)
    {
        return $this->getDateFormatter()
            ->formatDatetime($datetime, $formatDate, $formatTime, $timezone, $locale);
    }

    public function formatDate($date, $format = null, $timezone = null, $locale = null)
    {
        return $this->getDateFormatter()
            ->formatDate($date, $format, $timezone, $locale);
    }

    public function formatTime($time, $format = null, $timezone = null, $locale = null)
    {
        return $this->getDateFormatter()
            ->formatTime($time, $format, $timezone, $locale);
    }

    public function formatDateRange(array $dates, $formatDay = null, $formatMonth = null, $formatYear = null, $timezone = null, $locale = null)
    {
        return $this->getDateFormatter()
            ->formatDateRange($dates, $formatDay, $formatMonth, $formatYear, $timezone, $locale);
    }

    public function formatNumber($integer, $locale = null)
    {
        return $this->getNumberFormatter()
            ->formatNumber($integer, $locale);
    }

    public function formatInteger($number, $locale = null)
    {
        return $this->getNumberFormatter()
            ->formatInteger($number, $locale);
    }

    public function formatDecimal($decimal, $decimals = null, $locale = null)
    {
        return $this->getNumberFormatter()
            ->formatDecimal($decimal, $decimals, $locale);
    }

    public function formatCurrency($currency, $type = null, $locale = null)
    {
        return $this->getNumberFormatter()
            ->formatCurrency($currency, $type, $locale);
    }

    public function formatPercent($percent, $decimals = 2, $locale = null)
    {
        return $this->getNumberFormatter()
            ->formatPercent($percent, $decimals, $locale);
    }

    public function formatCountry($country, $locale = null)
    {
        return $this->getCountryFormatter()
            ->formatCountry($country, $locale);
    }

    public function formatLanguage($language, $locale = null)
    {
        return $this->getLanguageFormatter()
            ->formatLanguage($language, $locale);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'uam_i18n';
    }

    protected function getDateFormatter()
    {
        if (!$this->date_formatter) {
            $this->date_formatter = new DateTimeFormatter();
        }

        return $this->date_formatter;
    }

    protected function getNumberFormatter()
    {
        if (!$this->number_formatter) {
            $this->number_formatter = new NumberFormatter();
        }

        return $this->number_formatter;
    }

    protected function getCountryFormatter()
    {
        if (!$this->country_formatter) {
            $this->country_formatter = new CountryFormatter();
        }

        return $this->country_formatter;
    }

    protected function getLanguageFormatter()
    {
        if (!$this->language_formatter) {
            $this->language_formatter = new LanguageFormatter();
        }

        return $this->language_formatter;
    }
}
