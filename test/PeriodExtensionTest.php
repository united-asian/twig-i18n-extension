<?php

namespace UAM\Twig\Extension\I18n\test;

use UAM\Twig\Extension\I18n\PeriodExtension;

class PeriodExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider sameDateProvider
     */
    public function testSameDate($start_date, $end_date, $expected)
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

    public function sameDateProvider()
    {
        return array(
            array('02-09-2016', '02-09-2016', '2 September 2016'),
            array('15-12-2015', '15-12-2015', '15 December 2015'),
            array('10-10-2010', '10-10-2010', '10 October 2010'),
        );
    }
}
