<?php

namespace UAM\Twig\Extension\I18n\test\Formatter;

use PHPUnit_Framework_TestCase;

abstract class AbstractFormatterTestCase extends PHPUnit_Framework_TestCase
{
    protected $locale;
    protected $style;
    protected $formatter;
    protected $intlFormatter;

    public function __construct($name = null, array $data = array(), $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->locale = $this->getLocale();
        $this->intlFormatter = $this->getIntlFormatter();
    }

    public function setup()
    {
        $this->formatter = $this->getFormatter();
    }

    abstract protected function getFormatter();
    abstract protected function getIntlFormatter();
    abstract protected function getLocale();
}
