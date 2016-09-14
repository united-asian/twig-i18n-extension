<?php

namespace UAM\Twig\Extension\I18n\Formatter;

use Symfony\Component\Translation\Loader\JsonFileLoader;
use Symfony\Component\Translation\Translator;

class ByteFormatter extends AbstractFormatter
{
    const CATALOGUE = 'uam-18n';
    const ZERO = '0';
    const DEFAULT_FORMAT = 'h';

    private $translator;

    protected $units = array(
        'B' => 0,
        'K' => 1,
        'M' => 2,
        'G' => 3,
        'T' => 4,
        'P' => 5,
    );

    public function formatBytes($bytes, $format = self::DEFAULT_FORMAT, $locale = null)
    {
        $format = strtoupper($format);

        $locale = $this->getLocale($locale);

        if (!$bytes) {
            return static::ZERO.$this->trans('B', $locale);
        }

        if (!preg_match('/^(?:B|([KMGTP])B?)$/', $format, $matches)) {
            $format = self::DEFAULT_FORMAT;
        } else {
            if (isset($matches[1])) {
                $format = $matches[1];
            }
        }

        $units = $this->getUnits();

        if ($format == self::DEFAULT_FORMAT) {
            $pow = floor((log($bytes, 1024)));
            $format = array_search($pow, $units);
        }

        $converted_value = floor($bytes / pow(1024, $units[$format]));

        if ($converted_value < 1) {
            return $this->formatBytes($bytes, self::DEFAULT_FORMAT, $locale);
        }

        return $converted_value.$this->trans($format, $locale);
    }

    protected function getUnits()
    {
        return $this->units;
    }

    protected function trans($format, $locale)
    {
        $translator = $this->getTranslator();

        return $translator->trans($format, array(), static::CATALOGUE, $locale);
    }

    protected function getTranslator()
    {
        if (!$this->translator) {
            $this->translator = new Translator('en');

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
