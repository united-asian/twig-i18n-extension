<?php

namespace UAM\Twig\Extension\I18n\test;

use DateInterval;
use DateTime;
use PHPUnit_Framework_TestCase;
use UAM\Twig\Extension\I18n\PeriodExtension;

class PeriodExtensionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProvider
     */
    public function testPeriodFilter($start_date, $end_date, $day_format, $month_format, $year_format, $expected)
    {
        $translator = $this->getMockBuilder('Symfony\Component\Translation\TranslatorInterface')
            ->setMethods(array(
                'trans',
                'transChoice',
                'setLocale',
                'getLocale',
                ))
            ->getMock();

        $translator->expects($this->any())
            ->method('trans')
            ->willReturnCallback(array(
                $this,
                'trans',
            ));

        $extension = new PeriodExtension($translator);
        $actual = $extension->periodFilter($start_date, $end_date, 'en', $day_format, $month_format, $year_format);

        $this->assertEquals($expected, $actual);
    }

    public function trans($id, array $parameters = array(), $domain = null, $locale = null)
    {
        if ($id == PeriodExtension::DATE) {
             return $parameters['%to%'];
        }

        return $parameters['%from%'].' - '.$parameters['%to%'];
    }

    public function dataProvider()
    {
        return array(
            //start date is before end date
            array('2016-03-05', '2016-03-10', 'dd', 'MMMM', 'yyyy', '05 - 10 March 2016'),
            array('2016-03-05', '2016-03-10', 'd', 'MMMM', 'yyyy', '5 - 10 March 2016'),
            array('2015-05-30', '2016-03-04', 'dd', 'MMM', 'yyyy', '30 May 2015 - 04 Mar 2016'),
            array('2016-04-12', '2016-06-01', 'd', 'MMM', 'yyyy', '12 Apr - 1 Jun 2016'),
            array('2015-05-30', '2016-03-04', 'dd', 'MM', 'yyyy', '30 05 2015 - 04 03 2016'),
            array('2015-05-30', '2016-03-04', 'd', 'MM', 'yyyy', '30 05 2015 - 4 03 2016'),
            array('2015-05-30', '2016-03-04', 'dd', 'M', 'yyyy', '30 5 2015 - 04 3 2016'),
            array('2015-05-30', '2016-03-04', 'd', 'M', 'yy', '30 5 15 - 4 3 16'),
            array('2015-05-30', '2016-03-04', 'dd', 'MMMM', 'yy', '30 May 15 - 04 March 16'),
            array('2015-05-30', '2016-03-04', 'd', 'MMMM', 'yy', '30 May 15 - 4 March 16'),
            array('2015-05-30', '2016-03-04', 'dd', 'MMM', 'yy', '30 May 15 - 04 Mar 16'),
            array('2015-05-30', '2016-03-04', 'd', 'MMM', 'yy', '30 May 15 - 4 Mar 16'),
            array('2015-05-30', '2016-03-04', 'dd', 'MM', 'yy', '30 05 15 - 04 03 16'),
            array('2015-05-30', '2016-03-04', 'd', 'MM', 'yy', '30 05 15 - 4 03 16'),
            array('2015-05-30', '2016-03-04', 'dd', 'M', 'yy', '30 5 15 - 04 3 16'),
            array('2015-05-30', '2016-03-04', 'd', 'M', 'yy', '30 5 15 - 4 3 16'),

            //start date is same as end date
            array('2016-09-02', '2016-09-02', 'd', 'MMMM', 'yyyy', '2 September 2016'),
            array('2016-09-02', '2016-09-02', 'dd', 'MMMM', 'yyyy', '02 September 2016'),
            array('2016-09-02', '2016-09-02', 'd', 'MMM', 'yyyy', '2 Sep 2016'),
            array('2016-09-02', '2016-09-02', 'dd', 'MMM', 'yyyy', '02 Sep 2016'),
            array('2016-09-02', '2016-09-02', 'd', 'MM', 'yyyy', '2 09 2016'),
            array('2016-09-02', '2016-09-02', 'dd', 'M', 'yyyy', '02 9 2016'),
            array('2016-09-02', '2016-09-02', 'd', 'M', 'yy', '2 9 16'),

            //start date is after end date
            array('2017-05-02', '2015-03-10', 'dd', 'MMMM', 'yyyy','02 May 2017 - 10 March 2015'),
            array('2016-04-20', '016-04-10', 'd', 'MMM', 'yyyy','20 - 10 Apr 2016'),
            array('2016-05-05', '2016-04-10', 'd', 'M', 'yyyy', '5 5 - 10 4 2016'),

            //tests for now
            $this->getTestDataForNow('P1Y'),
            $this->getTestDataForNow('P1M'),
            $this->getTestDataForNow('P1D'),
         );
    }

    protected function getTestDataForNow($addition)
    {
        $from = new DateTime();

        $to = new DateTime();
        $to->add(new DateInterval($addition));

        $from_year = $from->format('Y');
        $to_year = $to->format('Y');

        $from_month = $from->format('F');
        $to_month = $to->format('F');

        $from_day = $from->format('d');
        $to_day = $to->format('d');

        if ($from_year != $to_year) {
            $expected = $from->format('d F Y') . ' - ' . $to->format('d F Y');
        } else {
            if ($from_month != $to_month) {
                $expected = $from->format('d F') . ' - ' . $to->format('d F Y');
            } else {
                if ($from_day != $to_day) {
                    $expected = $from->format('d') . ' - ' . $to->format('d F Y');
                }
            }
        }

        return array(
            'now',
            $to->format('Y-m-d'),
            'dd',
            'MMMM',
            'yyyy',
            $expected,
        );
    }
}
