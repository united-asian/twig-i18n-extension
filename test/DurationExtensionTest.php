<?php

namespace UAM\Twig\Extension\I18n\Test;

use PHPUnit_Framework_TestCase;
use UAM\Twig\Extension\I18n\DurationExtension;

class DurationExtensionTest extends PHPUnit_Framework_TestCase
{
    //dates span across a year
    public function differentDateDataProvider()
    {
        return array(
            array('8-12-2015', '6-5-2011', 'YYY-MMM-DDD', '4 years 7 months 2 days'),
            array('8-12-2015', '6-5-2011', 'YY-MM-DD', '4 yrs 7 mos 2 ds'),
            array('8-12-2015', '6-5-2011', 'Y-M-D', '4y 7m 2d'),
        );
    }

    /**
     * @dataProvider differentDateDataProvider
     */
    public function testDurationDifferentDate($from, $to, $format, $expect_duration)
    {
        $duration_filter = new DurationExtension();
        $total_duration = $duration_filter->durationFilter($from, $to, $format);

        $this->assertEquals($expect_duration, $total_duration);
    }
}
