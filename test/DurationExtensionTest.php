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
            array('2015-12-8', '2011-5-6', 'YYY-MMM-DDD', '4 years 7 months 2 days'),
            array('2015-12-8', '2011-5-6', 'YY-MM-DD', '4 yrs 7 mos 2 ds'),
            array('2015-12-8', '2011-5-6', 'Y-M-D', '4y 7m 2d'),

            // 1.using different rounding cases

            // rounding at seconds
            array('2015-2-10 12:10:00', '2015-2-10 12:10:35', 'YYY-MMM-DDD-HHH-III-SSS', '0 years 0 months 0 days 0 hours 0 minutes 35 seconds'),

            // rounding at minutes
            array('2015-2-10 12:10:00', '2015-2-10 12:20:00', 'YYY-MMM-DDD-HHH-III', '0 years 0 months 0 days 0 hours 10 minutes'),

            // rounding at hours
            array('2015-2-10 11:10:00', '2015-2-10 12:10:00', 'YYY-MMM-DDD-HHH', '0 years 0 months 0 days 1 hours'),

            // rounding at days
            array('2015-2-10', '2015-2-15', 'YYY-MMM-DDD', '0 years 0 months 5 days'),

            // rounding at months
            array('2015-2-10', '2015-12-10', 'YYY-MMM', '0 years 10 months'),

            // rounding at years
            array('2015-2-10', '2017-4-10', 'YYY', '2 years'),

            // 2. Ignoring higher units

            // showing only years
            array('2015-2-10', '2017-3-12', 'YYY', '2 years'),

            // showing only months
            array('2015-2-10', '2017-3-12', 'MMM', ' 25 months'),

            // showing only days
            array('2011-5-6','2012-5-6', 'DDD', ' 366 days'),

            // showing only seconds
            array('2011-5-6 12:15:00', '2015-12-8 02:05:30', 'SSS', ' 144856230 seconds'),

            // showing years and days
            array('2011-5-6 12:15:00', '2015-12-8 02:05:30', 'YYY-DDD', '4 years 8 days'),

            // showing months and seconds
            array('2011-5-6 12:15:00', '2015-12-8 02:05:30', 'MMM-SSS', ' 55 months 136230 seconds'),

            // showing days and seconds
            array('2011-5-6 12:15:00', '2015-12-8 02:05:30', 'DDD-SSS', ' 1676 days 49830 seconds'),

            // showing years, hours and seconds
            array('2011-5-6 12:15:00', '2015-12-8 02:05:30', 'YYY-HHH-SSS', '4 years 44 hours 3030 seconds'),

            // showing months, days and seconds
            array('2011-5-6 12:15:00', '2015-12-8 02:05:30', 'MMM-DDD-SSS', ' 55 months 1 days 49830 seconds'),
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
