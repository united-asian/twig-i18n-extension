<?php

namespace UAM\Twig\Extension\I18n\test;

use PHPUnit_Framework_TestCase;
use UAM\Twig\Extension\I18n\PeriodExtension;

class PeriodExtensionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProvider
     */
    public function testPeriodFilter($start_date, $end_date, $day_format, $month_format, $year_format, $expected_period)
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
        $actual_period = $extension->periodFilter($start_date, $end_date, 'en', $day_format, $month_format, $year_format);

        $this->assertEquals($expected_period, $actual_period);
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

            //when start date is same as end date
            array('02-09-2016', '02-09-2016', 'd', 'MMMM', 'yyyy', '2 September 2016'),
            array('02-09-2016', '02-09-2016', 'dd', 'MMMM', 'yyyy', '02 September 2016'),
            array('02-09-2016', '02-09-2016', 'd', 'MMM', 'yyyy', '2 Sep 2016'),
            array('02-09-2016', '02-09-2016', 'dd', 'MMM', 'yyyy', '02 Sep 2016'),
            array('02-09-2016', '02-09-2016', 'd', 'MM', 'yyyy', '2 09 2016'),
            array('02-09-2016', '02-09-2016', 'dd', 'M', 'yyyy', '02 9 2016'),
            array('02-09-2016', '02-09-2016', 'dd', 'M', 'yyy', '02 9 2016'),
            array('02-09-2016', '02-09-2016', 'd', 'M', 'yy', '2 9 16'),
            array('02-09-2016', '02-09-2016', 'dd', 'M', 'y', '02 9 2016'),

            //when start date is less than end date
            array('05-03-2016', '10-03-2016', 'd', 'MMMM', 'yyyy', '5 - 10 March 2016'),
            array('12-05-2015', '24-05-2015', 'dd', 'MMMM', 'yy','12 - 24 May 15'),
            array('12-04-2016', '01-06-2016', 'dd', 'MM', 'yyyy', '12 04 - 01 06 2016'),
            array('30-05-2015', '04-03-2016', 'd', 'MM', 'yyyy', '30 05 2015 - 4 03 2016'),

            //when start date is greater than end date
            array('05-02-2017', '10-03-2015', 'dd', 'MMMM', 'yyyy','05 February 2017 - 10 March 2015'),
            array('20-04-2016', '10-04-2016', 'd', 'MMM', 'yyyy','20 - 10 Apr 2016'),
            array('05-05-2016', '10-04-2016', 'd', 'M', 'yyyy', '5 5 - 10 4 2016'),
         );
    }
}
