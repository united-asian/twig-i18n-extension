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

            // 1.using different rounding cases

            // rounding at seconds
            array('10-2-2015 12:10:00', '10-2-2015 12:10:00', 'YYY-MMM-DDD-HHH-III-SSS', '0 years 0 months 0 days 0 hours 0 minutes 0 seconds'),
            array('10-2-2015 12:10:00', '10-2-2015 12:10:00', 'YY-MM-DD-HH-II-SS', '0 yrs 0 mos 0 ds 0 hrs 0 mins 0 secs'),
            array('10-2-2015 12:10:00', '10-2-2015 12:10:00', 'Y-M-D-H-I-S', '0y 0m 0d 0h 0i 0s'),

            // rounding at minutes
            array('10-2-2015', '10-2-2015', 'YYY-MMM-DDD-HHH-III', '0 years 0 months 0 days 0 hours 0 minutes'),
            array('10-2-2015', '10-2-2015', 'YY-MM-DD-HH-II', '0 yrs 0 mos 0 ds 0 hrs 0 mins'),
            array('10-2-2015', '10-2-2015', 'Y-M-D-H-I', '0y 0m 0d 0h 0i'),

            // rounding at hours
            array('10-2-2015', '10-2-2015', 'YYY-MMM-DDD-HHH', '0 years 0 months 0 days 0 hours'),
            array('10-2-2015', '10-2-2015', 'YY-MM-DD-HH', '0 yrs 0 mos 0 ds 0 hrs'),
            array('10-2-2015', '10-2-2015', 'Y-M-D-H', '0y 0m 0d 0h'),

            // rounding at days
            array('10-2-2015', '15-2-2015', 'YYY-MMM-DDD', '0 years 0 months 5 days'),
            array('10-2-2015', '15-2-2015', 'YY-MM-DD', '0 yrs 0 mos 5 ds'),
            array('10-2-2015', '15-2-2015', 'Y-M-D', '0y 0m 5d'),

            // rounding at months
            array('10-2-2015', '10-12-2015', 'YYY-MMM', '0 years 10 months'),
            array('10-2-2015', '10-12-2015', 'YY-MM', '0 yrs 10 mos'),
            array('10-2-2015', '10-12-2015', 'Y-M', '0y 10m'),

            // rounding at years
            array('10-2-2015', '10-4-2017', 'YYY', '2 years'),
            array('10-2-2015', '10-4-2017', 'YY', '2 yrs'),
            array('10-2-2015', '10-4-2017', 'Y', '2y'),

            // 2. Ignoring higher units

            // showing only years
            array('10-2-2015', '12-3-2017', 'YYY', '2 years'),
            array('10-2-2015', '12-3-2017', 'YY', '2 yrs'),
            array('10-2-2015', '12-3-2017', 'Y', '2y'),

            // showing only months
            array('10-2-2015', '12-3-2017', 'MMM', ' 25 months'),
            array('10-2-2015', '12-3-2017', 'MM', ' 25 mos'),
            array('10-2-2015', '12-3-2017', 'M', ' 25m'),

            // showing only days
            array('6-5-2011','6-5-2012', 'DDD', ' 366 days'),
            array('6-5-2011','6-5-2012', 'DD', ' 366 ds'),
            array('6-5-2011','6-5-2012', 'D', ' 366d'),

            // showing only seconds
            array('6-5-2011 12:15:00', '8-12-2015 02:05:30', 'SSS', ' 144856230 seconds'),
            array('6-5-2011 12:15:00', '8-12-2015 02:05:30', 'SS', ' 144856230 secs'),
            array('6-5-2011 12:15:00', '8-12-2015 02:05:30', 'S', ' 144856230s'),

            // showing years and days
            array('6-5-2011 12:15:00', '8-12-2015 02:05:30', 'YYY-DDD', '4 years 8 days'),
            array('6-5-2011 12:15:00', '8-12-2015 02:05:30', 'YY-DD', '4 yrs 8 ds'),
            array('6-5-2011 12:15:00', '8-12-2015 02:05:30', 'Y-D', '4y 8d'),

            // showing months and seconds
            array('6-5-2011 12:15:00', '8-12-2015 02:05:30', 'MMM-SSS', ' 55 months 136230 seconds'),
            array('6-5-2011 12:15:00', '8-12-2015 02:05:30', 'MM-SS', ' 55 mos 136230 secs'),
            array('6-5-2011 12:15:00', '8-12-2015 02:05:30', 'M-S', ' 55m 136230s'),

            // showing days and seconds
            array('6-5-2011 12:15:00', '8-12-2015 02:05:30', 'DDD-SSS', ' 1676 days 49830 seconds'),
            array('6-5-2011 12:15:00', '8-12-2015 02:05:30', 'DD-SS', ' 1676 ds 49830 secs'),
            array('6-5-2011 12:15:00', '8-12-2015 02:05:30', 'D-S', ' 1676d 49830s'),

            // showing years, hours and seconds
            array('6-5-2011 12:15:00', '8-12-2015 02:05:30', 'YYY-HHH-SSS', '4 years 44 hours 3030 seconds'),
            array('6-5-2011 12:15:00', '8-12-2015 02:05:30', 'YY-HH-SS', '4 yrs 44 hrs 3030 secs'),
            array('6-5-2011 12:15:00', '8-12-2015 02:05:30', 'Y-H-S', '4y 44h 3030s'),

            // showing months, days and seconds
            array('6-5-2011 12:15:00', '8-12-2015 02:05:30', 'MMM-DDD-SSS', ' 55 months 1 days 49830 seconds'),
            array('6-5-2011 12:15:00', '8-12-2015 02:05:30', 'MM-DD-SS', ' 55 mos 1 ds 49830 secs'),
            array('6-5-2011 12:15:00', '8-12-2015 02:05:30', 'M-D-S', ' 55m 1d 49830s'),
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
