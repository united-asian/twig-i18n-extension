<?php

namespace UAM\Twig\Extension\I18n\Test;

use DateInterval;
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

            // 3. start date and end date is same.

            // all units of start and end date is same
            array('2015-2-10 12:10:15', '2015-2-10 12:10:15', 'YYY-MMM-DDD-HHH-III-SSS', '0 years 0 months 0 days 0 hours 0 minutes 0 seconds'),

            // month and year of start and end date is same
            array('2015-4-6', '2015-4-27', 'YYY-MMM-DDD', '0 years 0 months 21 days'),

            // day and year of start and end date is same
            array('2014-12-10', '2014-2-10', 'YYY-MMM-DDD', '0 years 10 months 0 days'),

            // month and day of start and end date is same
            array('2014-12-10', '2015-12-10', "YYY-MMM-DDD", '1 years 0 months 0 days'),

            // 4. start date is greater than end date

            // year of start date is less than end date
            array('2016-10-2', '2014-5-3', 'YYY-MMM-DDD', '2 years 4 months 30 days'),

            // year is same but month of end date is less than start date
            array('2016-10-2', '2016-5-3', 'YYY-MMM-DDD', '0 years 4 months 30 days'),

            // year and month is same but day of end date is less than end date
            array('2016-9-10', '2016-9-6', 'YYY-MMM-DDD', '0 years 0 months 4 days'),

            // 5. test for null values

            // when start date is null
            array(
                null,
                $this->getCurrentDate()->add(new DateInterval('P2Y1M1D'))->format('Y-m-d H:i:s'),
                'YYY-MMM-DDD',
                '2 years 1 months 1 days',
            ),


            // when end date is null
            array(
                $this->getCurrentDate()->add(new DateInterval('P4Y1M1D'))->format('Y-m-d H:i:s'),
                null,
                'YYY-MMM-DDD',
                '4 years 1 months 1 days',
            ),

            // both start and end date is null
            array(null, null, "YYY-MMM-DDD", '0 years 0 months 0 days'),

            // 6. using various formats and orders

            // format of start date and end date is different
            array('2016-10-2', '2014/5/3', 'YYY-MMM-DDD', '2 years 4 months 30 days'),
            array('2016/10/2', '2014-5-3', 'YYY-MMM-DDD', '2 years 4 months 30 days'),

            // order of start date and end date is different
            array('2016-10-2', '3-5-2014', 'YYY-MMM-DDD', '2 years 4 months 30 days'),
            array('2016/10/2', '5/3/2014', 'YYY-MMM-DDD', '2 years 4 months 30 days'),

            // order and format of start date and end date is different
            array('2016-10-2', '5/3/2014', 'YYY-MMM-DDD', '2 years 4 months 30 days'),
            array('2016/10/2', '3-5-2014', 'YYY-MMM-DDD', '2 years 4 months 30 days'),

            // 7. using different displaying formats
            array('2015-12-8 11:10:20', '2011-5-6 12:11:30', 'YYY-MMM-DDD-HHH-III-SSS', '4 years 7 months 1 days 22 hours 58 minutes 50 seconds'),
            array('2015-12-8 11:10:20', '2011-5-6 12:11:30', 'YY-MM-DD-HH-II-SS', '4 yrs 7 mos 1 ds 22 hrs 58 mins 50 secs'),
            array('2015-12-8 11:10:20', '2011-5-6 12:11:30', 'Y-M-D-H-I-S', '4y 7m 1d 22h 58i 50s'),
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

    protected function getCurrentDate()
    {
        return new DateTime(null);
    }
}
