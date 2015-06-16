<?php

namespace UAM\Twig\Extension\I18n\test\Formatter;

use PHPUnit_Framework_TestCase;

abstract class AbstractFormatterTestCase extends PHPUnit_Framework_TestCase
{
    protected $locale;
    protected $formatter;
    protected $intlFormatter;

    public function __construct($name = null, array $data = array(), $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->intlFormatter = $this->getIntlFormatter();
        $this->locale = $this->getLocale();
    }

    public function setup()
    {
        $this->formatter = $this->getFormatter();
    }

    abstract protected function getFormatter();
    abstract protected function getIntlFormatter();
    abstract protected function getLocale();
}
