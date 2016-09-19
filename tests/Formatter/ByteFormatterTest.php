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
            //when format is B
            array(600, 'B', '600B'),
            array(1024, 'B', '1024B'),
            array(1024 * 1024, 'B', (1024 * 1024).'B'),
            array(1024 * 1024 * 1024, 'B', (1024 * 1024 * 1024).'B'),
            array(1024 * 1024 * 1024 * 1024, 'B', (1024 * 1024 * 1024 * 1024).'B'),
            array(1024 * 1024 * 1024 * 1024 * 1024, 'B', (1.1258999068426E+15).'B'),
            array((1024 * 1024 * 1024 * 1024 * 1024) + 1, 'B', (1.1258999068426E+15).'B'),

            //when format is b
            array(600, 'b', '600B'),
            array(1024, 'b', '1024B'),
            array(1024 * 1024, 'b', (1024 * 1024).'B'),
            array(1024 * 1024 * 1024, 'b', (1024 * 1024 * 1024).'B'),
            array(1024 * 1024 * 1024 * 1024, 'b', (1024 * 1024 * 1024 * 1024).'B'),

            //when format is K, Kb, KB
            array(9 * 1024, 'K', '9KB'),
            array(9 * 1024 + 512, 'Kb', '9KB'),
            array((9 * 1024 * 1024 * 1024) + 512, 'KB', (9 * 1024 * 1024).'KB'),

            //when format is k, kB, kb
            array(9 * 1000, 'k', '9KB'),
            array(9 * 1000 + 512, 'kb', '9KB'),
            array((9 * 1000 * 1000) + 512, 'kB', (9 * 1000).'KB'),

            //when format is M, MB, Mb
            array(12 * 1024 * 1024, 'M', '12MB'),
            array(12 * 1024 * 1024 * 1024, 'M', (1024 * 12).'MB'),
            array(12897484, 'Mb', '12MB'),
            array(12897484, 'MB', '12MB'),
            array(12897484, 'M', '12MB'),

            //when format is m, mb, mB
            array(14 * 1000, 'm', '0MB'),
            array((14 * 1000 * 1000) + 613, 'mb', '14MB'),
            array(14 * 1000 * 1000 * 1000, 'mB', (1000 * 14).'MB'),

            //when format is G, Gb, GB
            array(7 * 1024 * 1024, 'G', '0GB'),
            array(7 * 1024 * 1024 * 1024 * 1024, 'G', (1024 * 7).'GB'),
            array((7 * 1024 * 1024 * 1024 * 1024 * 1024 * 1024) + 897, 'G', (1024 * 1024 * 1024 * 7).'GB'),

            //when format is g, gB, gb
            array(13 * 1000 * 1000, 'g', '0GB'),
            array(13 * 1000 * 1000 * 1000 * 1000, 'gB', (1000 * 13).'GB'),
            array((13 * 1000 * 1000 * 1000 * 1000 * 1000 * 1000) + 123, 'gb', (1000 * 1000 * 1000 * 13).'GB'),

            //when format is T, Tb, TB
            array(7 * 1024 * 1024 * 1024, 'T', '0TB'),
            array(7 * 1024 * 1024 * 1024 * 1024, 'T', '7TB'),
            array((4 * 1024 * 1024 * 1024 * 1024 * 1024 * 1024) + 985, 'Tb', (1024 * 1024 * 4).'TB'),
            array(4 * 1024 * 1024 * 1024 * 1024 * 1024 * 1024 * 1024 * 1024, 'TB', (1024 * 1024 * 1024 * 1024 * 4).'TB'),

            //when format is t, tb, tB
            array(7 * 1000 * 1000 * 1000, 't', '0TB'),
            array(7 * 1000 * 1000 * 1000 * 1000, 'tb', '7TB'),
            array((4 * 1000 * 1000 * 1000 * 1000 * 1000 * 1000) + 985, 'tB', (1000 * 1000 * 4).'TB'),
            array(4 * 1000 * 1000 * 1000 * 1000 * 1000 * 1000 * 1000 * 1000, 't', (1000 * 1000 * 1000 * 1000 * 4).'TB'),

            //when format is P, PB, PB
            array((2 * 1024 * 1024 * 1024) + 985, 'P', '0PB'),
            array((2 * 1024 * 1024 * 1024 * 1024 * 1024 * 1024) + 985, 'P', (1024 * 2).'PB'),
            array((1 * 1024 * 1024 * 1024 * 1024 * 1024 * 1024 * 1024 * 1024), 'P', (1024 * 1024 * 1024 * 1).'PB'),

            //when format is p, pB, pb
            array((2 * 1000 * 1000 * 1000) + 985, 'p', '0PB'),
            array((2 * 1000 * 1000 * 1000 * 1000 * 1000 * 1000) + 985, 'pb', (1000 * 2).'PB'),
            array((1 * 1000 * 1000 * 1000 * 1000 * 1000 * 1000 * 1000 * 1000), 'pB', (1000 * 1000 * 1000 * 1).'PB'),

            //when bytes is in decimal form
            array((7 * 1024 * 1024 * 1024 * 1024) + .78983, 'G', (1024 * 7).'GB'),
            array((14 * 1000 * 1000 * 1000) + .4536, 'mB', (1000 * 14).'MB'),

            //when bytes is less than 1
            array(0, 'M', '0B'),
            array(0.98, 'KB', '0B'),
            array(-3 * 1024, 'M', '0B'),

            //when converted bytes value is less than 1 then
            array(603, 'M', '0MB'),
            array(1 * 1024, 'g', '0GB'),
            array(2 * 1024, 'P', '0PB'),

            //when format is h then
            array(600, 'h', '600B'),
            array(9 * 1000, 'h', '9KB'),
            array((7 * 1000 * 1000) + 987, 'h', '7MB'),
            array(7 * 1000 * 1000 * 1000, 'h', '7GB'),
            array(7 * 1000 * 1000 * 1000 * 1000 * 1000, 'h', '7PB'),

            //when format is H than
            array(600, 'H', '600B'),
            array(9 * 1024, 'H', '9KB'),
            array((7 * 1024 * 1024) + 987, 'H', '7MB'),
            array(7 * 1024 * 1024 * 1024, 'H', '7GB'),
            array((1024 * 1024 * 1024 * 1024 * 1024) + 1, 'H', '1PB'),

            //when format is other than specified format
            array(1 * 1000, 'Kp', '1KB'),
            array(96 * 1000 * 1000, 'AB', '96MB'),
            array((4 * 1000 * 1000 * 1000) + 123, 'O', '4GB'),

            //when format is null or empty
            array(6 * 1000 * 1000, '', '6MB'),
            array((7 * 1000 * 1000 * 1000 * 1000) + 68, null, '7TB'),

            //when bytes is not numeric
            array(null, 'GB', 'NaN'),
            array('', 'GB', 'NaN'),
            array('gh', 'T', 'NaN'),

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
            //when format is B
            array(600, 'B', '600o'),
            array(1024, 'B', '1024o'),
            array(1024 * 1024, 'B', (1024 * 1024).'o'),
            array(1024 * 1024 * 1024, 'B', (1024 * 1024 * 1024).'o'),
            array(1024 * 1024 * 1024 * 1024, 'B', (1024 * 1024 * 1024 * 1024).'o'),
            array(1024 * 1024 * 1024 * 1024 * 1024, 'B', (1.1258999068426E+15).'o'),
            array((1024 * 1024 * 1024 * 1024 * 1024) + 1, 'B', (1.1258999068426E+15).'o'),

            //when format is b
            array(600, 'b', '600o'),
            array(1024, 'b', '1024o'),
            array(1024 * 1024, 'b', (1024 * 1024).'o'),
            array(1024 * 1024 * 1024, 'b', (1024 * 1024 * 1024).'o'),
            array(1024 * 1024 * 1024 * 1024, 'b', (1024 * 1024 * 1024 * 1024).'o'),

            //when format is K, Kb, KB
            array(9 * 1024, 'K', '9Ko'),
            array(9 * 1024 + 512, 'Kb', '9Ko'),
            array((9 * 1024 * 1024 * 1024) + 512, 'KB', (9 * 1024 * 1024).'Ko'),

            //when format is k, kB, kb
            array(9 * 1000, 'k', '9Ko'),
            array(9 * 1000 + 512, 'kb', '9Ko'),
            array((9 * 1000 * 1000) + 512, 'kB', (9 * 1000).'Ko'),

            //when format is M, MB, Mb
            array(12 * 1024 * 1024, 'M', '12Mo'),
            array(12 * 1024 * 1024 * 1024, 'M', (1024 * 12).'Mo'),
            array(12897484, 'Mb', '12Mo'),
            array(12897484, 'MB', '12Mo'),
            array(12897484, 'M', '12Mo'),

            //when format is m, mb, mB
            array(14 * 1000, 'm', '0Mo'),
            array((14 * 1000 * 1000) + 613, 'mb', '14Mo'),
            array(14 * 1000 * 1000 * 1000, 'mB', (1000 * 14).'Mo'),

            //when format is G, Gb, GB
            array(7 * 1024 * 1024, 'G', '0Go'),
            array(7 * 1024 * 1024 * 1024 * 1024, 'G', (1024 * 7).'Go'),
            array((7 * 1024 * 1024 * 1024 * 1024 * 1024 * 1024) + 897, 'G', (1024 * 1024 * 1024 * 7).'Go'),

            //when format is g, gB, gb
            array(13 * 1000 * 1000, 'g', '0Go'),
            array(13 * 1000 * 1000 * 1000 * 1000, 'gB', (1000 * 13).'Go'),
            array((13 * 1000 * 1000 * 1000 * 1000 * 1000 * 1000) + 123, 'gb', (1000 * 1000 * 1000 * 13).'Go'),

            //when format is T, Tb, TB
            array(7 * 1024 * 1024 * 1024, 'T', '0To'),
            array(7 * 1024 * 1024 * 1024 * 1024, 'T', '7To'),
            array((4 * 1024 * 1024 * 1024 * 1024 * 1024 * 1024) + 985, 'Tb', (1024 * 1024 * 4).'To'),
            array(4 * 1024 * 1024 * 1024 * 1024 * 1024 * 1024 * 1024 * 1024, 'TB', (1024 * 1024 * 1024 * 1024 * 4).'To'),

            //when format is t, tb, tB
            array(7 * 1000 * 1000 * 1000, 't', '0To'),
            array(7 * 1000 * 1000 * 1000 * 1000, 'tb', '7To'),
            array((4 * 1000 * 1000 * 1000 * 1000 * 1000 * 1000) + 985, 'tB', (1000 * 1000 * 4).'To'),
            array(4 * 1000 * 1000 * 1000 * 1000 * 1000 * 1000 * 1000 * 1000, 't', (1000 * 1000 * 1000 * 1000 * 4).'To'),

            //when format is P, PB, PB
            array((2 * 1024 * 1024 * 1024) + 985, 'P', '0Po'),
            array((2 * 1024 * 1024 * 1024 * 1024 * 1024 * 1024) + 985, 'P', (1024 * 2).'Po'),
            array((1 * 1024 * 1024 * 1024 * 1024 * 1024 * 1024 * 1024 * 1024), 'P', (1024 * 1024 * 1024 * 1).'Po'),

            //when format is p, pB, pb
            array((2 * 1000 * 1000 * 1000) + 985, 'p', '0Po'),
            array((2 * 1000 * 1000 * 1000 * 1000 * 1000 * 1000) + 985, 'pb', (1000 * 2).'Po'),
            array((1 * 1000 * 1000 * 1000 * 1000 * 1000 * 1000 * 1000 * 1000), 'pB', (1000 * 1000 * 1000 * 1).'Po'),

            //when bytes is in decimal form
            array((7 * 1024 * 1024 * 1024 * 1024) + .78983, 'G', (1024 * 7).'Go'),
            array((14 * 1000 * 1000 * 1000) + .4536, 'mB', (1000 * 14).'Mo'),

            //when bytes is less than 1
            array(0, 'M', '0o'),
            array(0.98, 'KB', '0o'),
            array(-3 * 1024, 'M', '0o'),

            //when converted bytes value is less than 1 then
            array(603, 'M', '0Mo'),
            array(1 * 1024, 'g', '0Go'),
            array(2 * 1024, 'P', '0Po'),

            //when format is h then
            array(600, 'h', '600o'),
            array(9 * 1000, 'h', '9Ko'),
            array((7 * 1000 * 1000) + 987, 'h', '7Mo'),
            array(7 * 1000 * 1000 * 1000, 'h', '7Go'),
            array(7 * 1000 * 1000 * 1000 * 1000 * 1000, 'h', '7Po'),

            //when format is H than
            array(600, 'H', '600o'),
            array(9 * 1024, 'H', '9Ko'),
            array((7 * 1024 * 1024) + 987, 'H', '7Mo'),
            array(7 * 1024 * 1024 * 1024, 'H', '7Go'),
            array((1024 * 1024 * 1024 * 1024 * 1024) + 1, 'H', '1Po'),

            //when format is other than specified format
            array(1 * 1000, 'Kp', '1Ko'),
            array(96 * 1000 * 1000, 'AB', '96Mo'),
            array((4 * 1000 * 1000 * 1000) + 123, 'O', '4Go'),

            //when format is null or empty
            array(6 * 1000 * 1000, '', '6Mo'),
            array((7 * 1000 * 1000 * 1000 * 1000) + 68, null, '7To'),

            //when bytes is not numeric
            array(null, 'GB', 'NaN'),
            array('', 'GB', 'NaN'),
            array('gh', 'T', 'NaN'),

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
