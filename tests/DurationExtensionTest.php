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

    /**
     * @dataProvider intervalDaysData
     */
    public function testDateIntervalDays($from, $to, $expected)
    {
        $locale = 'en';

        $interval = $this->getExtension()
            ->getDateInterval($from, $to);

        $this->assertEquals($expected, $interval->days);
    }

    public function intervalData()
    {
        return array(
            // array($from, $to, $days, $y, $m, $d)
            array('2015-01-01', '2015-01-31', 31, 0, 1, 0),
            array('2015-01-01', '2015-02-28', 59, 0, 2, 0),
            array('2016-01-01', '2016-02-28', 59, 0, 1, 28),
            array('2016-01-01', '2016-02-29', 60, 0, 2, 0),
            array('2015-01-02', '2015-02-02', 32, 0, 1, 0),
            array('2015-01-15', '2015-02-15', 32, 0, 1, 0),
            array('2015-01-28', '2015-02-28', 32, 0, 1, 0),
            array('2016-01-28', '2016-02-28', 32, 0, 1, 0),
            array('2016-01-28', '2016-02-29', 33, 0, 1, 1),
            array('2016-01-29', '2016-02-29', 32, 0, 1, 0),
            array('2016-01-30', '2016-02-29', 31, 0, 1, 0),
            array('2016-01-31', '2016-02-29', 30, 0, 1, 0),

            array('2015-01-01', '2015-03-28', 87, 0, 2, 28),
            array('2015-01-01', '2015-03-31', 90, 0, 3, 0),
            array('2016-01-01', '2016-03-28', 88, 0, 2, 28),
            array('2016-01-01', '2016-03-31', 91, 0, 3, 0),
            array('2015-01-01', '2015-12-31', 365, 1, 0, 0),
            array('2016-01-01', '2016-12-31', 366, 1, 0, 0),
            array('2016-01-02', '2016-12-30', 364, 0, 11, 29),
            array('2009-03-01', '2009-03-31', 31, 0, 1, 0),
        );
    }

    /**
     * @dataProvider intervalData
     */
    public function testDateInterval($from, $to, $days, $y, $m, $d)
    {
        $interval = $this->getExtension()
            ->getDateInterval($from, $to);

        $this->assertEquals($days, $interval->days);

        $this->assertEquals($y, $interval->y);
        $this->assertEquals($m, $interval->m);
        $this->assertEquals($d, $interval->d);
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
     * @dataProvider fakerDataEn
     */
    public function testFakerDataEn($from, $to, $format, $expected)
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

            // FIXME [OP 2016-09-11] This fails
            // array('2010-01-01', '2010-03-01', 'M', '2m'),

            array('2010-01-01', '2010-01-01', 'D', '0d'),

            // FIXME [OP 2016-09-11] This fails
            // array('2010-01-01', '2010-01-02', 'D', '2d'),

            // FIXME [OP 2016-09-11] This fails
            // array('2010-01-01', '2010-01-03', 'D', '3d'),

            array('2015-12-8', '2011-5-6', 'YYY-MMM-DDD', '4 years 7 months 2 days'),
            array('2015-12-8', '2011-5-6', 'YY-MM-DD', '4 yrs 7 mos 2 days'),
            array('2015-12-8', '2011-5-6', 'Y-M-D', '4y 7m 2d'),

            // 1.using different rounding cases

            // rounding at seconds
            array('2015-2-10 12:10:00', '2015-2-10 12:10:35', 'YYY-MMM-DDD-HHH-III-SSS', '0 year 0 month 0 day 0 hour 0 minute 35 seconds'),

            // rounding at minutes
            array('2015-2-10 12:10:00', '2015-2-10 12:20:00', 'YYY-MMM-DDD-HHH-III', '0 year 0 month 0 day 0 hour 10 minutes'),

            // rounding at hours
            array('2015-2-10 11:10:00', '2015-2-10 12:10:00', 'YYY-MMM-DDD-HHH', '0 year 0 month 0 day 1 hour'),

            // rounding at days
            array('2015-2-10', '2015-2-15', 'YYY-MMM-DDD', '0 year 0 month 5 days'),

            // rounding at months
            array('2015-2-10', '2015-12-10', 'YYY-MMM', '0 year 10 months'),

            // rounding at years
            array('2015-2-10', '2017-4-10', 'YYY', '2 years'),

            // 2. Ignoring higher units

            // showing only years
            array('2015-2-10', '2017-3-12', 'YYY', '2 years'),

            // showing only months
            array('2015-2-10', '2017-3-12', 'MMM', '25 months'),

            // showing only days
            array('2011-5-6','2012-5-6', 'DDD', '366 days'),
            array('2011-5-6', '2012-5-6', 'DDD', '366 days'),

            // showing only seconds
            array('2011-5-6 12:15:00', '2015-12-8 02:05:30', 'SSS', '144856230 seconds'),

            // showing years and days
            array('2011-5-6 12:15:00', '2015-12-8 02:05:30', 'YYY-DDD', '4 years 8 days'),

            // showing months and seconds
            array('2011-5-6 12:15:00', '2015-12-8 02:05:30', 'MMM-SSS', '55 months 136230 seconds'),

            // showing days and seconds
            array('2011-5-6 12:15:00', '2015-12-8 02:05:30', 'DDD-SSS', '1676 days 49830 seconds'),

            // showing years, hours and seconds
            array('2011-5-6 12:15:00', '2015-12-8 02:05:30', 'YYY-HHH-SSS', '4 years 44 hours 3030 seconds'),

            // showing months, days and seconds
            array('2011-5-6 12:15:00', '2015-12-8 02:05:30', 'MMM-DDD-SSS', '55 months 1 day 49830 seconds'),

            // 3. start date and end date is same.

            // all units of start and end date is same
            array('2015-2-10 12:10:15', '2015-2-10 12:10:15', 'YYY-MMM-DDD-HHH-III-SSS', '0 year 0 month 0 day 0 hour 0 minute 0 second'),

            // month and year of start and end date is same
            array('2015-4-6', '2015-4-27', 'YYY-MMM-DDD', '0 year 0 month 21 days'),

            // day and year of start and end date is same
            array('2014-12-10', '2014-2-10', 'YYY-MMM-DDD', '0 year 10 months 0 day'),

            // month and day of start and end date is same
            array('2014-12-10', '2015-12-10', "YYY-MMM-DDD", '1 year 0 month 0 day'),

            array('2014-12-10', '2015-12-10', 'YYY-MMM-DDD', '1 year 0 month 0 day'),

            // 4. start date is greater than end date

            // year of start date is less than end date
            array('2016-10-2', '2014-5-3', 'YYY-MMM-DDD', '2 years 4 months 30 days'),

            // year is same but month of end date is less than start date
            array('2016-10-2', '2016-5-3', 'YYY-MMM-DDD', '0 year 4 months 30 days'),

            // year and month is same but day of end date is less than end date
            array('2016-9-10', '2016-9-6', 'YYY-MMM-DDD', '0 year 0 month 4 days'),

            // 5. test for null values

            // when start date is null
            array(
                null,
                $this->getCurrentDate()->add(new DateInterval('P2Y1M1DT1M'))->format('Y-m-d H:i:s'),
                'YYY-MMM-DDD',
                '2 years 1 month 1 day',
            ),

            // when end date is null
            array(
                $this->getCurrentDate()->add(new DateInterval('P4Y1M1DT1M'))->format('Y-m-d H:i:s'),
                null,
                'YYY-MMM-DDD',
                '4 years 1 month 1 day',
            ),

            // both start and end date is null
            array(null, null, "YYY-MMM-DDD", '0 year 0 month 0 day'),

            array(null, null, 'YYY-MMM-DDD', '0 year 0 month 0 day'),

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
            array('2015-12-8 11:10:20', '2011-5-6 12:11:30', 'YYY-MMM-DDD-HHH-III-SSS', '4 years 7 months 1 day 22 hours 58 minutes 50 seconds'),
            array('2015-12-8 11:10:20', '2011-5-6 12:11:30', 'YY-MM-DD-HH-II-SS', '4 yrs 7 mos 1 day 22 hrs 58 mins 50 secs'),
            array('2015-12-8 11:10:20', '2011-5-6 12:11:30', 'Y-M-D-H-I-S', '4y 7m 1d 22h 58m 50s'),

            // 8. when start date or end date is incomplete

            // only year and month is provided
            array('2011-5', '2015-12-8', 'YYY-MMM-DDD', '4 years 7 months 7 days'),
            array('2011-5-6', '2015-12', 'YYY-MMM-DDD', '4 years 6 months 25 days'),
            array('2011-5', '2015-12', 'YYY-MMM-DDD', '4 years 7 months 0 day'),

            // only month and day is provided
            array('6/13', '2011-6-13', 'YYY-MMM-DDD', '5 years 0 month 0 day'),
            array('5/13/2016', '6/13', 'YYY-MMM-DDD', '0 year 1 month 0 day'),
            array('3/6', '5/7', 'YYY-MMM-DDD', '0 year 2 months 1 day'),

            //special cases
            array('2010-01-03 00:00:00', '2010-01-05 23:59:59', 'Y-M-D-H-I-S', '0y 0m 2d 23h 59m 59s'),
            array('2010-01-03 00:00:00', '2010-01-06 00:00:00', 'Y-M-D', '0y 0m 3d'),
            array('2010-01-03 00:00:00', '2010-01-06 00:00:01', 'Y-M-D-S', '0y 0m 3d 1s'),
            array('2010-01-03 01:00:00', '2010-01-03 02:00:00', 'Y-M-D-H', '0y 0m 0d 1h'),
            array('2010-01-01 03:00:00', '2010-01-01 03:00:01', 'Y-M-D-H-I-S', '0y 0m 0d 0h 0m 1s'),
            array('2010-01-03', '2010-01-05', 'y-m-d', '0y 0m 2d'),
            array('2010-01-01', '2010-01-31', 'y-m-d', '0y 0m 30d'),
            array('2015-01-01', '2015-12-31', 'y-m-d', '0y 11m 29d'),
            array('2016-01-01', '2016-12-31', 'y-m-d', '0y 11m 29d'),
            array('2010-12-01', '2011-01-01', 'y-m-d', '0y 1m 0d'),
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
                $from->format('Y-m-d'),
                $to->format('Y-m-d'),
                $intervals[1],
                $intervals[2]
            );
        }

        return $data;
    }

    public function fakerDataEn()
    {
        $faker = $this->getFaker();

        $data = array();

        foreach ($this->getIntervalsDataEn() as $intervals) {

            $from = $faker->dateTime();

            $to = clone($from);
            $interval = new DateInterval($intervals[0]);
            $to->add($interval);

            $data[] = array(
                $from->format('Y-m-d h:i:s'),
                $to->format('Y-m-d h:i:s'),
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

            // FIXME [OP 2016-09-12] This fails
            array('2015-01-01', '2015-12-31', 'D', '365j'),

            // FIXME [OP 2016-09-13] This fails
            array('2015-01-01 00:00:00', '2015-12-31 23:59:59', 'D', '365j'),

            // FIXME [OP 2016-09-12] This fails
            array('2016-01-01', '2016-12-31', 'D', '366j'),

            array('2010-01-01', '2010-01-02', 'M', '0m'),
            array('2010-01-01', '2010-02-01', 'M', '1m'),

            // FIXME [OP 2016-09-11] This fails
            array('2010-01-01', '2010-03-01', 'M', '2m'),

            array('2010-01-01', '2010-01-01', 'D', '0j'),

            // FIXME [OP 2016-09-11] This fails
            array('2010-01-01', '2010-01-02', 'D', '2j'),

            // FIXME [OP 2016-09-11] This fails
            array('2010-01-01', '2010-01-03', 'D', '3j'),
        );
    }

    public function intervalDaysData()
    {
        return array(
            // time not specified
            array('2015-1-1', '2015-12-31', 365),
            array('2016-1-1', '2016-12-31', 366),
            array('2016-1-2', '2016-12-30', 364),
            array('2016-3-1', '2016-6-1', 93),
            array('2016-1-1', '2024-1-1', 2923),
            array('2016-1-1', '2024-3-1', 2983),
            array('2016-1-1', '2020-1-1', 1462),
            array('2011-1-1', '2015-1-1', 1462),
            array('2009-3-1', '2009-3-31', 31),
            array('2015-1-1', '2015-3-1', 60),
            array('2015-2-25', '2015-3-1', 5),
            array('2016-3-25', '2016-4-1', 8),
            array('2010-1-3', '2010-1-5', 3),

            // time is specified
            array('2015-1-1 00:00:00', '2015-1-2 2:20:00', 1),
            array('2010-1-3 2:00:00', '2010-1-5', 2),
            array('2016-3-25', '2016-4-1 23:59:59', 7),
            array('2015-2-25', '2015-3-1', 5),
            array('2016-1-1 3:01:05', '2024-3-1 4:02:15', 2982),
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
            array('P1M13D', 'M', '2m'), // FIXME [OP 2016-09-11] Fails

            array('PT6H', 'D', '0j'),
            array('P1D', 'D', '1j'),  // FIXME [OP 2016-09-11] Fails
            array('P1DT23H', 'D', '1j'), //FIXME [OP 2016-09-11] Fails
            array('P2D', 'D', '2j'), // FIXME [OP 2016-09-11] Fails
        );
    }

    protected function getIntervalsDataEn()
    {
        return array(
            array('P1D', 'Y', '0y'),
            array('P1Y', 'Y', '1y'),
            array('P1Y1M', 'Y', '1y'),
            array('P1Y2M10D', 'Y', '1y'),
            array('P1Y1D', 'Y', '1y'),
            array('P2Y', 'Y', '2y'),
            array('P2Y5M', 'Y', '2y'),
            array('P2Y3M8D', 'Y', '2y'),
            array('P2Y3D', 'Y', '2y'),

            array('P1D', 'M', '0m'),
            array('P1M', 'M', '1m'),
            array('P1M3D', 'M', '1m'),
            array('P1M18D', 'M', '2m'),

            array('PT6H', 'D', '0d'),
            array('P1D', 'D', '1d'),
            array('P1DT23H', 'D', '1d'),
            array('P2D', 'D', '2d'),

            array('PT2M', 'H', '0h'),
            array('PT6H', 'H', '6h'),
            array('PT1H2M', 'H', '1h'),

            array('PT2S', 'I', '0m'),
            array('PT2M', 'I', '2m'),
            array('PT10M55S', 'I', '11m'),
            array('PT3M4S', 'I', '3m'),

            array('P1Y2M25D', 'Y-M', '1y 3m'),
            array('P1Y2M2D', 'Y-M', '1y 2m'),
            array('P5MT5H2M20S', 'M-H', '5m 5h'),
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
