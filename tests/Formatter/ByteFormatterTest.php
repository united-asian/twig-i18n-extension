<?php

namespace UAM\Twig\Extension\I18n\Test\Formatter;

use UAM\Twig\Extension\I18n\Formatter\ByteFormatter;

class ByteFormatterTest extends AbstractFormatterTestCase
{
    /**
     * @dataProvider bytesProviderEn()
     */
    public function testBytesFilterEn($number, $format, $expected)
    {
        $locale = 'en';

        $this->assertEquals(
            $expected,
            $this->getFormatter()->formatBytes($number, $format, $locale)
        );
    }

    /**
     * @dataProvider bytesProviderFr()
     */
    public function testBytesFilterFr($number, $format, $expected)
    {
        $locale = 'fr';

        $this->assertEquals(
            $expected,
            $this->getFormatter()->formatBytes($number, $format, $locale)
        );
    }

    public function bytesProviderEn()
    {
        return array(
            //when format is B and b
            array(1048576, 'B', '1048576B'),
            array(1048576, 'b', '1048576B'),
            array(600, 'B', '600B'),

            //when format is k, kb, Kb, KB, kB
            array(9728, 'k', '9KB'),
            array(9728, 'kb', '9KB'),
            array(9728, 'Kb', '9KB'),
            array(9728, 'KB', '9KB'),
            array(9728, 'kB', '9KB'),

            //when format is m, M, mb, MB, mB
            array(12897484, 'm', '12MB'),
            array(12897484, 'M', '12MB'),
            array(12897484, 'mb', '12MB'),
            array(12897484, 'MB', '12MB'),
            array(12897484, 'mb', '12MB'),

            //when format is g, G, Gb, GB, gB
            array(16106127360, 'g', '15GB'),
            array(16106127360, 'G', '15GB'),
            array(16106127360, 'Gb', '15GB'),
            array(16106127360, 'GB', '15GB'),
            array(16106127360, 'gB', '15GB'),

            //when format is t, T, Tb, TB, tB
            array(7916483719987.2, 't', '7TB'),
            array(7916483719987.2, 'T', '7TB'),
            array(7916483719987.2, 'Tb', '7TB'),
            array(7916483719987.2, 'TB', '7TB'),
            array(7916483719987.2, 'tB', '7TB'),

            //when format is p, P, Pb, PB, pB
            array(9002199254340800, 'p', '7PB'),
            array(9002199254340800, 'P', '7PB'),
            array(9002199254340800, 'Pb', '7PB'),
            array(9002199254340800, 'PB', '7PB'),
            array(9002199254340800, 'pB', '7PB'),

            //when formatted bytes is less than 1 then automatically convert into nearest human readable
            array(2048, 'M', '2KB'),

            //when format is h then automatically convert into nearest human readable
            array(2048, 'h', '2KB'),
            array(2048, 'H', '2KB'),

            //when bytes is null
            array('', 'KB', '0B'),

            //when format is other than specified format then
            array(2048, 'A', '2KB'),

            //when bytes and format is null
            array('', '', '0B'),
            array('', null, '0B'),
            array(null, 'h', '0B'),
            array(null, null, '0B'),
        );
    }

    public function bytesProviderFr()
    {
        return array(
            //when format is B and b
            array(1048576, 'B', '1048576o'),
            array(1048576, 'b', '1048576o'),
            array(600, 'B', '600o'),

            //when format is k, K, kb, Kb, KB, kB
            array(9728, 'k', '9Ko'),
            array(9728, 'K', '9Ko'),
            array(9728, 'kb', '9Ko'),
            array(9728, 'Kb', '9Ko'),
            array(9728, 'KB', '9Ko'),
            array(9728, 'kB', '9Ko'),

            //when format is m, M, Mb, mb, MB, mB
            array(12897484, 'm', '12Mo'),
            array(12897484, 'M', '12Mo'),
            array(12897484, 'Mb', '12Mo'),
            array(12897484, 'mb', '12Mo'),
            array(12897484, 'MB', '12Mo'),
            array(12897484, 'mb', '12Mo'),

            //when format is g, G, gb, Gb, GB, gB
            array(16106127360, 'g', '15Go'),
            array(16106127360, 'G', '15Go'),
            array(16106127360, 'gb', '15Go'),
            array(16106127360, 'Gb', '15Go'),
            array(16106127360, 'GB', '15Go'),
            array(16106127360, 'gB', '15Go'),

            //when format is t, T, Tb, tb, TB, tB
            array(7916483719987.2, 't', '7To'),
            array(7916483719987.2, 'T', '7To'),
            array(7916483719987.2, 'tb', '7To'),
            array(7916483719987.2, 'Tb', '7To'),
            array(7916483719987.2, 'TB', '7To'),
            array(7916483719987.2, 'tB', '7To'),

            //when format is p, P, Pb, pb,  PB, pB
            array(9002199254340800, 'p', '7Po'),
            array(9002199254340800, 'P', '7Po'),
            array(9002199254340800, 'Pb', '7Po'),
            array(9002199254340800, 'pb', '7Po'),
            array(9002199254340800, 'PB', '7Po'),
            array(9002199254340800, 'pB', '7Po'),

            //when formatted bytes is less than 1 then automatically convert into nearest human readable
            array(2048, 'M', '2Ko'),

            //when format is h then automatically convert into nearest human readable
            array(2048, 'h', '2Ko'),
            array(2048, 'H', '2Ko'),

            //when bytes is null
            array('', 'KB', '0o'),

            //when format is other than specified format
            array(2048, 'A', '2Ko'),

            //when bytes and format is null
            array('', '', '0o'),
            array('', null, '0o'),
            array(null, 'h', '0o'),
            array(null, null, '0o'),
        );
    }

    protected function setupFormatter()
    {
        return new ByteFormatter();
    }
}
