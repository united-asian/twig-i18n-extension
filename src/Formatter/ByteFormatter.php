<?php

namespace UAM\Twig\Extension\I18n\Formatter;

use Symfony\Component\Translation\Loader\JsonFileLoader;
use Symfony\Component\Translation\Translator;

class ByteFormatter extends AbstractFormatter
{
    const CATALOGUE = 'uam-18n';
    const ZERO = '0';

    private $translator;

    protected $units = array(
        'B' => 0,
        'K' => 1,
        'M' => 2,
        'G' => 3,
        'T' => 4,
        'P' => 5,
    );

    public function formatBytes($bytes, $format = 'h', $locale = null)
    {
        $format = strtoupper($format);

        $locale = $this->getLocale($locale);

        $unit = $this->getUnit();

        if (!$bytes) {
            return static::ZERO.$this->trans('B', $locale);
        }

        if (!preg_match('/^(?:B|([KMGTP])B?)$/', $format, $matches)) {
            $format = $this->getAppropriateFormat($bytes, $unit);
        } else {
            if (isset($matches[1])) {
                $format = $matches[1];
            }
        }

        $converted_value = $this->getConvertedValue($bytes, $format, $unit);

        if ($converted_value < 1) {
            $format = $this->getAppropriateFormat($bytes, $unit);
            $converted_value = $this->getConvertedValue($bytes, $format, $unit);
        }

        return $converted_value.$this->trans($format, $locale);
    }

    protected function getAppropriateFormat($bytes, $unit)
    {
        $pow = floor((log($bytes, 1024)));

        return array_search($pow, $unit);
    }
    protected function getConvertedValue($bytes, $format, $units)
    {
        return floor($bytes / pow(1024, $units[$format]));
    }

    protected function getUnit()
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
