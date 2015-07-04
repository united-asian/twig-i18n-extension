<?php

namespace UAM\Twig\Extension\I18n\Test\Formatter;

use PHPUnit_Framework_TestCase;
use Symfony\Component\Intl\Locale\Locale;

abstract class AbstractFormatterTestCase extends PHPUnit_Framework_TestCase
{
    protected $formatter;

    /**
     * @dataProvider localeProvider
     */
    public function testGetLocale($locale)
    {
        $this->assertEquals(
            $this->getFormatter()->getLocale($locale),
            $locale
        );
    }

    public function testGetDefaultLocale()
    {
        $this->assertEquals(
            $this->getFormatter()->getLocale(),
            Locale::getDefault()
        );
    }

    public function localeProvider()
    {
        return array(
            array('en'),
            array('fr'),
            array('de')
        );
    }

    abstract protected function setupFormatter();
//    abstract protected function getIntlFormatter();
//    abstract protected function getLocale();

    protected function setUp()
    {
        $this->formatter = $this->setupFormatter();
    }

    protected function getFormatter()
    {
        return $this->formatter;
    }
}
