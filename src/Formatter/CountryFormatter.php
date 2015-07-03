<?php

namespace UAM\Twig\Extension\I18n\Formatter;

use Symfony\Component\Intl\Intl;

/**
 * Formats country code to localized country name.
 */
class CountryFormatter extends AbstractFormatter
{
    public function formatCountry($country, $locale = null)
    {
        $translated = Intl::getRegionBundle()->getCountryName(strtoupper($country), $locale);

        return $translated ? $translated : $country;
    }
}
