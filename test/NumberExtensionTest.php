<?php

namespace UAM\Twig\Extension\I18n\Test;

use PHPUnit_Framework_TestCase;
use UAM\Twig\Extension\I18n\NumberExtension;

class NumberExtensionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProvider()
     */
    public function testByteFilter($number, $decimals, $unit, $expected)
    {
        $extension = new NumberExtension();
        $formatted_value = $extension->bytesFilter($number, $decimals, $unit);

        $this->assertEquals($formatted_value, $expected);
    }

    public function dataProvider()
    {
        return array(
            array(12345678, '', 'B', '12345678B'),
            array(123, '', '', '123B'),
            array(123456789012345, '','Kb', '120563270519.87Kb')
        );
    }
}
