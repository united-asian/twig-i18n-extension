<?php

namespace UAM\Twig\Extension\I18n\Formatter;

use Symfony\Component\Translation\Loader\JsonFileLoader;
use Symfony\Component\Translation\Translator;

class ByteFormatter extends AbstractFormatter
{
    const CATALOGUE = 'uam-18n';
    const ZERO = '0';
    const DEFAULT_FORMAT = 'h';
    const ERROR = 'bytes.error';

    private $translator;

    protected $units = array(
        'B' => 1,
        'K' => 1024,
        'M' => 1048576,  //1024 * 1024
        'G' => 1073741824,  //1024 * 1024 * 1024
        'T' => 1099511627776,   //1024 * 1024 * 1024 * 1024
        'P' => 1125899906842624,    //1024 * 1024 * 1024 * 1024 * 1024,
        'b' => 1,
        'k' => 1000,
        'm' => 1000000, //1000 * 1000,
        'g' => 1000000000,  //1000 * 1000 * 1000,
        't' => 1000000000000,   //1000 * 1000 * 1000 * 1000,
        'p' => 1000000000000000,    //1000 * 1000 * 1000 * 1000 * 1000,
    );

    public function formatBytes($bytes, $format = self::DEFAULT_FORMAT, $locale = null)
    {
        if (!is_numeric($bytes)) {
            return $this->trans(static::ERROR, $locale);
        }

        $locale = $this->getLocale($locale);

        if ($bytes < 1) {
            return static::ZERO.$this->trans('bytes.unit.b', $locale);
        }

        $units = $this->getUnits();

        $format = $this->getFormat($bytes, $format);

        if (ctype_upper($format)) {
            $converted_value = floor($bytes / $units[$format]);
        } else {
            $converted_value = floor($bytes / $units[$format]);
        }

        return $converted_value.$this->trans('bytes.unit'.'.'.strtolower($format), $locale);
    }

    protected function getUnits()
    {
        return $this->units;
    }

    protected function getFormat($bytes, $format)
    {
        if (!preg_match('/^(?:[BbH]|([KMGTPkmgtp])[Bb]?)$/', $format, $matches)) {
            $format = self::DEFAULT_FORMAT;
        } else {
            if (isset($matches[1])) {
                $format = $matches[1];
            }
        }

        $units = $this->getUnits();

        $base = $this->getBase($format);

        if ($format == self::DEFAULT_FORMAT || $format == 'H') {
            $pow = floor((log($bytes, $base)));
            $format = array_search(pow($base, $pow), $units);
        }

        return $format;
    }

    protected function getBase($format)
    {
        if ($format == self::DEFAULT_FORMAT) {
            $base = 1000;
        } else {
            $base = 1024;
        }

        return $base;
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
                dirname(__FILE__).'/../uam-i18n.en.json',
                'en',
                static::CATALOGUE
            );

            $this->translator->addResource(
                'json',
                dirname(__FILE__).'/../uam-i18n.fr.json',
                'fr',
                static::CATALOGUE
            );
        }

        return $this->translator;
    }
}
