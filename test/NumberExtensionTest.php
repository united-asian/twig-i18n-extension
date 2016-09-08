<?php

namespace UAM\Twig\Extension\I18n\Test;

use PHPUnit_Framework_TestCase;
use UAM\Twig\Extension\I18n\NumberExtension;

class NumberExtensionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProvider()
     */
    public function testByteFilter($number, $unit, $expected, $locale = null)
    {
        $extension = new NumberExtension();
        $formatted_value = $extension->bytesFilter($number, $unit, $locale);

        $this->assertEquals($formatted_value, $expected);
    }

    public function dataProvider()
    {
        return array(
            array(1048576, 'b', '1048576b'),
            array(9728, 'Kb', '9Kb'),
            array(12897484,'Mb', '12Mb'),
            array(16106127360, 'Gb', '15Gb'),
            array(7916483719987.2, 'Tb', '7Tb'),
            array(1759218604441.6, 'Pb', '0Pb'),
        );
    }
}
