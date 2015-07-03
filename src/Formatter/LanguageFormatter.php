<?php

namespace UAM\Twig\Extension\I18n\Formatter;

use Exception;
use Symfony\Component\Intl\Intl;

/**
 * Formats a language code.
 */
class LanguageFormatter extends AbstractFormatter
{
    public function formatLanguage($language, $locale = null)
    {
        try {
            return Intl::getLanguageBundle()->getName($language, $locale);
        } catch (Exception $e) {
            return $language;
        }
    }
}
