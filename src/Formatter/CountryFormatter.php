<?php

namespace UAM\Twig\Extension\I18n\Formatter;

use Symfony\Component\Locale\Locale;

/**
 * Formats country code to localized country name.
 */
class CountryFormatter extends AbstractFormatter
{
    public function formatCountry($country, $locale = null)
    {
        $locale = $this->getLocale($locale);
        $locale = Locale::getPrimaryLanguage($locale);
        $countries = Locale::getDisplayCountries($locale);

        if (isset($countries[$country])) {
            return $countries[$country];
        }

        return $country;
    }
}
