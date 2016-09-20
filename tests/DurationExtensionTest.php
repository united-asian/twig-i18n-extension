<?php

namespace UAM\Twig\Extension\I18n\Test;

use DateInterval;
use DateTime;
use PHPUnit_Framework_TestCase;
use Faker\Factory;
use UAM\Twig\Extension\I18n\DurationExtension;

class DurationExtensionTest extends PHPUnit_Framework_TestCase
{
    protected $extension;

    protected $faker;

    public function intervalDaysData()
    {
        return array(
            // time not specified
            array('2015-1-1', '2015-12-31', 'D', 365),
            array('2016-1-1', '2016-12-31', 'D', 365+1),
            array('2016-1-2', '2016-12-30', 'D', 364),
            array('2016-3-1', '2016-6-1', 'D', 31+30+31+1),
            array('2016-1-1', '2024-1-1', 'D', 365*8+2+1),
            array('2016-1-1', '2020-1-1', 'D', 365*4+2),
            array('2011-1-1', '2015-1-1', 'D', 365*4+1+1),
            array('2009-3-1', '2009-3-31', 'D', 31),
            array('2015-1-1', '2015-3-1', 'D', 31+28+1),
            array('2015-2-25', '2015-3-1', 'D', 5),
            array('2016-3-25', '2016-4-1', 'D', 8),
            array('2010-1-3', '2010-1-5', 'D', 3),

            // time is specified
            array('2015-1-1 00:00:00', '2015-1-2 2:20:00', 'D', 1),
            array('2010-1-3 2:00:00', '2010-1-5', 'D', 2),
            array('2016-3-25', '2016-4-1 23:59:59', 'D', 7),
            array('2015-2-25', '2015-3-1', 'D', 5),
            array('2016-1-1 3:01:05', '2024-3-1 4:02:15', 'D', 365*8+31+28+3),
        );
    }

    public function intervalDataYears()
    {
        return array(
             //array($from, $to, $days, $y, $m, $d)
            array('2015-01-01', '2015-01-31', 'Y', 0),
            array('2015-01-02', '2015-02-02', 'Y', 0),
            array('2015-01-15', '2015-02-15', 'Y', 0),
            array('2015-01-01', '2015-12-31', 'Y', 1),
            array('2016-01-02', '2016-12-30', 'Y', 1),
            array('2014-01-03', '2014-12-02', 'Y', 1),
            array('2009-03-01', '2009-03-31', 'Y', 0),
            array('2015-03-01', '2016-02-28', 'Y', 1),
        );
    }

    public function intervalDataMonths()
    {
        return array(
            //array($from, $to, $format, $m)
            array('2015-01-01', '2015-01-31', 'Y-M', 1),
            array('2015-01-01', '2015-02-28', 'Y-M', 2),
            array('2016-01-01', '2016-02-29', 'Y-M', 2),
            array('2015-01-02', '2015-02-02', 'Y-M', 1),
            array('2016-01-28', '2016-02-28', 'Y-M', 1),
            array('2016-01-01', '2016-03-28', 'Y-M', 3),
            array('2016-01-01', '2016-03-31', 'Y-M', 3),
            array('2009-03-01', '2009-03-31', 'Y-M', 1),

            array('2015-01-01', '2015-12-31', 'Y-M', 0),
            array('2016-01-01', '2016-12-31', 'Y-M', 0),
            array('2016-01-02', '2016-12-30', 'Y-M', 12),
            array('2009-03-01', '2009-03-31', 'Y-M', 1),
            array('2015-03-01', '2016-02-28', 'Y-M', 0),
        );
    }

    /**
     * @dataProvider intervalDaysData
     */
    public function testDateIntervalDays($from, $to, $format, $expected)
    {
        $interval = $this->getExtension()
            ->getDateInterval($from, $to, $format);

        $this->assertEquals($expected, $interval->days);
    }

    /**
     * @dataProvider intervalDataYears
     */
    public function testDateIntervalYears($from, $to, $format, $y)
    {
        $interval = $this->getExtension()
            ->getDateInterval($from, $to, $format);

        $this->assertEquals($y, $interval->y);
    }

    /**
     * @dataProvider intervalDataMonths
     */
    public function testDateIntervalMonths($from, $to, $format, $m)
    {
        $interval = $this->getExtension()
            ->getDateInterval($from, $to, $format);

        $this->assertEquals($m, $interval->m);
    }

    /**
     * @dataProvider dataEn
     */
    public function testDurationEn($from, $to, $format, $expected)
    {
        $locale = 'en';

        $actual = $this->getExtension()
            ->duration($from, $to, $format, $locale);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @dataProvider fakerDataFr
     */
    public function testFakerDataFr($from, $to, $format, $expected)
    {
        $locale = 'fr';

        $actual = $this->getExtension()
            ->duration($from, $to, $format, $locale);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @dataProvider dataFr
     */
    public function testDataFr($from, $to, $format, $expected)
    {
        $locale = 'fr';

        $actual = $this->getExtension()
            ->duration($from, $to, $format, $locale);

        $this->assertEquals($expected, $actual);
    }

    // data provider for 'en' locale
    public function dataEn()
    {
        return array(
            array('2010-01-01', '2010-01-01', 'Y', '0y'),
            array('2010-01-01', '2011-01-01', 'Y', '1y'),
            array('2010-01-01', '2012-01-01', 'Y', '2y'),

            array('2010-01-01', '2010-01-02', 'M', '0m'),
            array('2010-01-01', '2010-02-01', 'M', '1m'),
            array('2010-01-01', '2010-03-01', 'M', '2m'),
            array('2010-01-01', '2010-01-01', 'D', '1d'),
            array('2010-01-01', '2010-01-02', 'D', '2d'),
            array('2010-01-01', '2010-01-03', 'D', '3d'),
            array('2011-5-6', '2015-12-8', 'Y-M-D', '4y 7m 3d'),

            // 1.using different rounding cases

            // rounding at seconds
            array('2015-2-10 12:10:00', '2015-2-10 12:10:35', 'Y-M-D-H-I-S', '0y 0m 0d 0h 0m 35s'),

            // rounding at minutes
            array('2015-2-10 12:10:00', '2015-2-10 12:20:00', 'Y-M-D-H-I', '0y 0m 0d 0h 10m'),

            // rounding at hours
            array('2015-2-10 11:10:00', '2015-2-10 12:10:00', 'Y-M-D-H', '0y 0m 0d 1h'),

            // rounding at days
            array('2015-2-10', '2015-2-15', 'Y-M-D', '0y 0m 6d'),

            // rounding at months
            array('2015-2-10', '2016-3-10', 'M', '13m'),

            // rounding at years
            array('2015-2-10', '2017-4-10', 'Y', '2y'),

            // 2. Ignoring higher units

            // showing only years
            array('2015-2-10', '2017-3-12', 'YYY', '2 years'),

            // showing years and months
            array('2016-01-01', '2016-02-29', 'Y-M', '0y 2m'),

            // showing years and days
            array('2010-01-01', '2011-02-02', 'Y-D', '1y 32d'),

            // showing only months
            array('2015-2-10', '2017-3-12', 'MMM', '25 months'),

            // showing only days
            array('2011-5-6','2012-5-5', 'D', '366d'),
            array('2011-5-6', '2012-5-5', 'D', '366d'),

            // showing only seconds
            array('2011-5-6 12:15:00', '2015-12-8 02:05:30', 'S', '144856230s'),

            // showing months and seconds
            array('2011-5-6 12:15:00', '2015-12-8 02:05:30', 'M-S', '55m 136230s'),

            // showing days and seconds
            array('2011-5-6 12:15:00', '2015-12-8 02:05:30', 'D-S', '1676d 49830s'),

            // showing years, hours and seconds
            array('2011-5-6 12:15:00', '2015-12-8 02:05:30', 'Y-H-S', '4y 44h 3030s'),

            // showing months, days and seconds
            array('2011-5-6 12:15:00', '2015-12-8 02:05:30', 'M-D-S', '55m 1d 49830s'),

            // 3. start date and end date is same.

            // all units of start and end date is same
            array('2015-2-10 12:10:15', '2015-2-10 12:10:15', 'Y-M-D-H-I-S', '0y 0m 0d 0h 0m 0s'),

            // month and year of start and end date is same
            array('2015-4-6', '2015-4-27', 'Y-M-D', '0y 0m 22d'),

            // day and year of start and end date is same
            array('2014-2-10', '2014-12-9', 'Y-M-D', '0y 10m 0d'),

            // month and day of start and end date is same
            array('2014-12-10', '2015-12-09', "Y-M-D", '1y 0m 0d'),

            array('2014-12-10', '2015-12-09', 'Y-M-D', '1y 0m 0d'),

            // 4. start date is greater than end date

            // year of start date is less than end date
            array('2016-10-2', '2014-5-3', 'Y-M-D', '2y 5m 0d'),

            // year is same but month of end date is less than start date
            array('2016-10-2', '2016-5-3', 'Y-M-D', '0y 5m 0d'),

            // year and month is same but day of end date is less than end date
            array('2016-9-10', '2016-9-6', 'Y-M-D', '0y 0m 5d'),

            // 5. test for null values

            // when start date is null
            array(
                null,
                $this->getCurrentDate()->add(new DateInterval('P2Y1M1D'))->format('Y-m-d'),
                'Y-M-D',
                '2y 1m 2d',
            ),

            // when end date is null
            array(
                $this->getCurrentDate()->add(new DateInterval('P4Y1M1D'))->format('Y-m-d'),
                null,
                'Y-M-D',
                '4y 1m 2d',
            ),

            // both start and end date is null
            array(null, null, "Y-M-D", '0y 0m 1d'),

            // 6. using various formats and orders

            // format of start date and end date is different
            array('2016-10-2', '2014/5/3', 'Y-M-D', '2y 5m 0d'),
            array('2016/10/2', '2014-5-3', 'Y-M-D', '2y 5m 0d'),

            // order of start date and end date is different
            array('2016-10-2', '3-5-2014', 'Y-M-D', '2y 5m 0d'),
            array('2016/10/2', '5/3/2014', 'Y-M-D', '2y 5m 0d'),

            // order and format of start date and end date is different
            array('2016-10-2', '5/3/2014', 'Y-M-D', '2y 5m 0d'),
            array('2016/10/2', '3-5-2014', 'Y-M-D', '2y 5m 0d'),

            // 7. using different displaying formats
            array('2015-12-8 11:10:20',
             '2011-5-6 12:11:30',
             'YYY-MMM-DDD-HHH-III-SSS',
             '4 years 7 months 1 day 22 hours 58 minutes 50 seconds'),
            array('2015-12-8 11:10:20',
             '2011-5-6 12:11:30',
             'YY-MM-DD-HH-II-SS', '4 yrs 7 mos 1 day 22 hrs 58 mins 50 secs'),
            array('2015-12-8 11:10:20', '2011-5-6 12:11:30', 'Y-M-D-H-I-S', '4y 7m 1d 22h 58m 50s'),

            // 8. when start date or end date is incomplete

            // only year and month is provided
            array('2011-5', '2015-12-8', 'Y-M-D', '4y 7m 8d'),
            array('2011-5-6', '2015-12', 'Y-M-D', '4y 6m 26d'),
            array('2011-5', '2015-12', 'Y-M-D', '4y 7m 1d'),

            // only month and day is provided
            array('6/13', '2011-6-13', 'Y-M-D', '5y 0m 1d'),
            array('5/13/2016', '6/13', 'Y-M-D', '0y 1m 1d'),
            array('3/6', '5/7', 'Y-M-D', '0y 2m 2d'),

           // special cases
            array('2010-01-03 00:00:00', '2010-01-05 23:59:59', 'Y-M-D-H-I-S', '0y 0m 2d 23h 59m 59s'),
            array('2010-01-03 00:00:00', '2010-01-06 00:00:00', 'Y-M-D', '0y 0m 3d'),
            array('2010-01-03 00:00:00', '2010-01-06 00:00:01', 'Y-M-D-S', '0y 0m 3d 1s'),
            array('2010-01-03 01:00:00', '2010-01-03 02:00:00', 'Y-M-D-H', '0y 0m 0d 1h'),
            array('2010-01-01 03:00:00', '2010-01-01 03:00:01', 'Y-M-D-H-I-S', '0y 0m 0d 0h 0m 1s'),
            array('2010-01-03', '2010-01-05', 'y-m-d', '0y 0m 3d'),
            array('2010-01-01', '2010-01-31', 'y-m-d', '0y 1m 0d'),
            array('2015-01-01', '2015-12-31', 'y-m-d', '1y 0m 0d'),
            array('2016-01-01', '2016-12-31', 'y-m-d', '1y 0m 0d'),
            array('2010-2-01', '2010-3-01', 'y-m-d', '0y 0m 29d'),
            array('2010-01-01', '2010-12-20', 'y', '1y'),
            array('2010-01-01', '2010-03-25', 'm', '3m'),
        );
    }

    // data provider for 'fr' locale
    public function fakerDataFr()
    {
        $faker = $this->getFaker();

        $data = array();

        foreach ($this->getIntervalsDataFr() as $intervals) {

            $from = $faker->dateTime();

            $to = clone($from);
            $interval = new DateInterval($intervals[0]);
            $to->add($interval);

            $data[] = array(
                $from->format('Y-m-d H:m:s'),
                $to->format('Y-m-d H:m:s'),
                $intervals[1],
                $intervals[2]
            );
        }

        return $data;
    }

    public function dataFr()
    {
        return array(
            array('2010-01-01', '2010-01-01', 'Y', '0a'),
            array('2010-01-01', '2011-01-01', 'Y', '1a'),
            array('2010-01-01', '2012-01-01', 'Y', '2a'),
            array('2015-01-01', '2015-12-31', 'D', '365j'),
            array('2016-01-01', '2016-12-31', 'D', '366j'),
            array('2010-01-01', '2010-01-02', 'M', '0m'),
            array('2010-01-01', '2010-02-01', 'M', '1m'),
            array('2010-01-01', '2010-03-01', 'M', '2m'),
            array('2010-01-01', '2010-01-01', 'D', '1j'),
            array('2010-01-01', '2010-01-02', 'D', '2j'),
            array('2010-01-01', '2010-01-03', 'D', '3j'),
        );
    }

    protected function getIntervalsDataFr()
    {
        return array(
            array('P1D', 'Y', '0a'),
            array('P1Y', 'Y', '1a'),
            array('P1Y1M', 'Y', '1a'),
            array('P1Y2M10D', 'Y', '1a'),
            array('P1Y1D', 'Y', '1a'),
            array('P2Y', 'Y', '2a'),
            array('P2Y5M', 'Y', '2a'),
            array('P2Y3M8D', 'Y', '2a'),
            array('P2Y3D', 'Y', '2a'),

            array('P1D', 'M', '0m'),
            array('P1M', 'M', '1m'),
            array('P1M3D', 'M', '1m'),
            array('P2M', 'M', '2m'),
            array('P1M13D', 'M', '1m'),

            array('PT6H', 'D', '0j'),
            array('P1D', 'D', '1j'),
            array('P1DT23H', 'D', '1j'),
            array('P2D', 'D', '2j'),
        );
    }

    protected function getCurrentDate()
    {
        return new DateTime(null);
    }

    protected function getExtension()
    {
        if (!$this->extension) {
            $this->extension = new DurationExtension();
        }

        return $this->extension;
    }

    protected function getFaker()
    {
        if (!$this->faker) {
            $this->faker = Factory::create();
        }

        return $this->faker;
    }
}
