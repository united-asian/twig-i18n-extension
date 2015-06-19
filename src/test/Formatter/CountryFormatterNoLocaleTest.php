<?php

namespace UAM\Twig\Extension\I18n\test\Formatter;

class CountryFormatterNoLocaleTest extends AbstractCountryFormatterTestCase
{
    protected function getLocale()
    {
        return null;
    }
}
