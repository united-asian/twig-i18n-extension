<?php

namespace UAM\Twig\Extension\I18n\test;

use UAM\Twig\Extension\I18n\PeriodExtension;

class PeriodExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dateProvider
     *
     */
    public function testDate($start_date, $end_date, $expected)
    {
        $this->getMockBuilder(PeriodExtension::class)
            ->disableOriginalConstructor()
            ->getMock();

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

        $this->assertEquals($expected, $extension->periodFilter($start_date, $end_date));
    }

    public function trans($id, array $parameters = array(), $domain = null, $locale = null)
    {
        if ($id == PeriodExtension::DATE) {
             return $parameters['%to%'];
        }

        return $parameters['%from%'].' - '.$parameters['%to%'];
    }

    public function dateProvider()
    {
        return array(
            //when start date is same as end date
            array('02-09-2016', '02-09-2016', '2 September 2016'),
            array('15-12-2015', '15-12-2015', '15 December 2015'),

            //when start date is less than end date
            array('05-03-2016', '10-03-2016', '5 - 10 March 2016'),
            array('12-05-2015', '24-05-2015', '12 - 24 May 2015'),
            array('12-04-2016', '01-06-2016', '12 April - 1 June 2016'),
            array('30-05-2015', '04-03-2016', '30 May 2015 - 4 March 2016'),

            //when start date is greater than end date
            array('05-02-2017', '10-03-2015', '5 February 2017 - 10 March 2015'),
            array('20-04-2016', '10-04-2016', '20 - 10 April 2016'),
            array('05-05-2016', '10-04-2016', '5 May - 10 April 2016'),
        );
    }
}
