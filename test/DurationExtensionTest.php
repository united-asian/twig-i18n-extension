<?php

namespace UAM\Twig\Extension\I18n\Test;

use DateInterval;
use DateTime;
use PHPUnit_Framework_TestCase;
use Faker\Factory;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Yaml\Yaml;
use UAM\Twig\Extension\I18n\DurationExtension;

class DurationExtensionTest extends PHPUnit_Framework_TestCase
{
    protected $extension;

    protected $faker;

    /**
     * @dataProvider enData
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
    public function enData()
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

    public function dataFr()
    {
        return array(
            array('2010-01-01', '2010-01-01', 'Y', '0a'),
            array('2010-01-01', '2011-01-01', 'Y', '1a'),
            array('2010-01-01', '2012-01-01', 'Y', '2a'),

            // FIXME [OP 2016-09-12] This fails
            array('2015-01-01', '2015-12-31', 'D', '365d'),

            // FIXME [OP 2016-09-12] This fails
            array('2016-01-01', '2016-12-31', 'D', '366d'),

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
