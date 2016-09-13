<?php

$data = array(
	array('2010-01-03', '2010-01-05'),
	array('2010-01-03 00:00:00', '2010-01-05 23:59:59'),
	array('2010-01-03 00:00:00', '2010-01-06 00:00:00'),
	array('2015-01-01', '2015-12-31'),
	array('2016-01-01', '2016-12-31'),
);

foreach ($data as $raw) {
	$start = $raw[0];
	$end = $raw[1];

	echo sprintf("\n%s > %s\n", $start, $end);

	$parsed = date_parse($start);

	if (!is_int($parsed['hour'])) {
		$start .= ' 00:00:00';
	}

	$date_start = new DateTime($start);

	$parsed = date_parse($end);

	if (!is_int($parsed['hour'])) {
		$end .= ' 23:59:59';
	}

	$date_end = new DateTime($end);
	$date_end->modify('+1 second');

	$interval = date_diff($date_start, $date_end);

	echo sprintf("%s > %s\n", $start, $end);
	print_r($interval);
}
