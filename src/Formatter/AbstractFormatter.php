<?php

namespace UAM\Twig\Extension\I18n\Formatter;

use Symfony\Component\Intl\Locale\Locale;

/**
 * Abstract class for formatter.
 */
abstract class AbstractFormatter
{
    public function getLocale($locale = null)
    {
        if (!($locale)) {
            $locale = Locale::getDefault();
        }

        return $locale;
    }
}
