<?php

namespace  UAM\Twig\Extension\I18n\test\Formatter;

use Exception;
use Locale;
use Faker\Factory;
use Symfony\Component\Intl\Intl;
use UAM\Twig\Extension\I18n\Formatter\LanguageFormatter;
use UAM\Twig\Extension\I18n\Test\Formatter\AbstractFormatterTestCase;

class LanguageFormatterTest extends AbstractFormatterTestCase
{
    /**
     * @dataProvider languageProvider
     */
    public function testFormatLanguage($language, $locale)
    {
        $this->assertEquals(
            $this->getFormatter()->formatLanguage($language, $locale),
            $this->getLanguage($language, $locale)
        );
    }

    /**
     * @dataProvider languageProvider
     */
    public function testFormatLanguageWithDefaultLocale($language, $locale)
    {
        $this->assertEquals(
            $this->getFormatter()->formatLanguage($language),
            $this->getLanguage($language, Locale::getDefault())
        );
    }

    public function languageProvider()
    {
        $faker = Factory::create();

        $countries = array();

        for ($i = 0; $i < 10; ++$i) {
            $countries[] = array(
                $faker->locale(),
                $faker->locale(),
            );
        }

        return $countries;
    }

    protected function getLanguage($language, $locale)
    {
        try {
            return Intl::getLanguageBundle()->getName($language, $locale);
        } catch (Exception $e) {
            return $language;
        }
    }

    protected function setupFormatter()
    {
        return new LanguageFormatter();
    }
}
