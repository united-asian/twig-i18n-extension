<?php

namespace UAM\Twig\Extension\I18n\Test;

use DateTime;
use PHPUnit_Framework_TestCase;
use UAM\Twig\Extension\I18n\DurationExtension;

class DurationExtensionTest extends PHPUnit_Framework_TestCase
{
    //dates span across a year
    public function testDurationDataProvider()
    {
        return array(
            array('8-12-2015', '6-5-2011', 'YYY-MMM-DDD', '4 years 7 months 2 days'),
            array('8-12-2015', '6-5-2011', 'YY-MM-DD', '4 yrs 7 mos 2 ds'),
            array('8-12-2015', '6-5-2011', 'Y-M-D', '4y 7m 2d'),
        );
    }

    /**
     * @dataProvider testDurationDataProvider
     */
    public function testDuration($from, $to, $format, $expected)
    {
        $extension = new DurationExtension();
        $actual = $extension->durationFilter($from, $to, $format);

        $this->assertEquals($expected, $actual);
    }
}
