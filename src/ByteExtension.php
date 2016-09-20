<?php

namespace UAM\Twig\Extension\I18n;

use Twig_Extension;
use Twig_SimpleFilter;
use UAM\Twig\Extension\I18n\Formatter\ByteFormatter;

class ByteExtension extends Twig_Extension
{
    protected $byte_formatter;

    public function getFilters()
    {
        return array(
            new Twig_SimpleFilter('bytes', array($this, 'formatBytes'), array('is_safe' => array('html'))),
        );
    }

    public function formatBytes($bytes, $unit = null, $locale = null)
    {
        return $this->getByteFormatter()
            ->formatBytes($bytes, $unit, $locale);
    }

    protected function getByteFormatter()
    {
        if (!$this->byte_formatter) {
            $this->byte_formatter = new ByteFormatter();
        }

        return $this->byte_formatter;
    }

    public function getName()
    {
        return 'bytes';
    }
}
