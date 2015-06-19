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
        $countries = Intl::getRegionBundle()->getCountryNames($this->getLocale($locale));

        if (isset($countries[strtoupper($country)])) {
            return $countries[strtoupper($country)];
        }

        return $country;
    }
}
