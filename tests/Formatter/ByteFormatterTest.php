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
            array(9 * 1024, 'k', '9KB'),
            array(9 * 1024 + 512, 'k', '9KB'),
            array((9 * 1024  * 1024) + 512, 'k', (9 * 1024).'KB'),
            array(9 * 1024, 'kb', '9KB'),
            array(9 * 1024, 'Kb', '9KB'),
            array(9 * 1024, 'KB', '9KB'),
            array(9 * 1024, 'kB', '9KB'),

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

            //when bytes is in decimal form
            array(4058367.987, 'Mb', '3MB'),
            array(78678.879, 'K','76KB'),

            //when bytes is less than 1
            array(0, 'M', '0B'),
            array(0.98, 'KB', '0B'),
            array(-3067, 'M', '0B'),

            //when converted bytes value is less than 1 then automatically convert into nearest human readable
            array(2048, 'M', '2KB'),

            //when format is h then automatically convert into nearest human readable
            array(2048, 'h', '2KB'),
            array(2089, 'H', '2KB'),

            //when format is other than specified format
            array(1048, 'kp', '1KB'),
            array(98762, 'AB', '96KB'),
            array(43762, 'O', '42KB'),

            //when bytes is not numeric
            array(null, 'GB', 'NaN'),
            array('', 'GB', 'NaN'),
            array('gh', 'T', 'NaN'),

            //when format is null or empty
            array(6893223, '', '6MB'),
            array(98234, null, '95KB'),

            //when bytes and format both null or empty
            array('', '', 'NaN'),
            array(null, null, 'NaN'),
            array('', null, 'NaN'),
            array(null, '', 'NaN'),
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

            //when bytes is in decimal form
            array(4058367.987, 'Mb', '3Mo'),
            array(78678.879, 'Kb', '76Ko'),

            //when bytes is less than 1
            array(0,'M', '0o'),
            array(0.98, 'KB', '0o'),
            array(-3067, 'M', '0o'),

            //when converted bytes value is less than 1 then automatically convert into nearest human readable
            array(2048, 'M', '2Ko'),
            array(54783,'TB','53Ko'),

            //when format is h then automatically convert into nearest human readable
            array(2048, 'h', '2Ko'),
            array(2089, 'H', '2Ko'),

            //when format is other than specified format
            array(1048, 'kp', '1Ko'),
            array(98762, 'AB', '96Ko'),
            array(43762, 'O', '42Ko'),

            //when bytes is not numeric
            array(null, 'GB', 'NaN'),
            array('', 'GB', 'NaN'),
            array('gh', 'T', 'NaN'),

            //when format is null or empty
            array(6893223, '', '6Mo'),
            array(98234, null, '95Ko'),

            //when bytes and format both null or empty
            array('', '', 'NaN'),
            array(null, null, 'NaN'),
            array('', null, 'NaN'),
            array(null, '', 'NaN'),
        );
    }

    protected function setupFormatter()
    {
        return new ByteFormatter();
    }
}
