<?php

include 'common.php';

$lines = get_input(1, true);
$sum   = 0;
foreach ($lines as $y => $line) {
	$first = 0;
	$last = 0;
	for ($x = 0; $x < strlen($line); $x++) {
		$substr = substr($line, $x);
		if (in_array($line[$x], ['1', '2', '3', '4', '5', '6', '7', '8', '9']) === true) {
			$first = ($line[$x] * 10);
			break;
		} else if (str_starts_with($substr, 'one') === true) {
			$first = 10;
			break;
		} else if (str_starts_with($substr, 'two')) {
			$first = 20;
			break;
		} else if (str_starts_with($substr, 'three')) {
			$first = 30;
			break;
		} else if (str_starts_with($substr, 'four')) {
			$first = 40;
			break;
		} else if (str_starts_with($substr, 'five')) {
			$first = 50;
			break;
		} else if (str_starts_with($substr, 'six')) {
			$first = 60;
			break;
		} else if (str_starts_with($substr, 'seven')) {
			$first = 70;
			break;
		} else if (str_starts_with($substr, 'eight')) {
			$first = 80;
			break;
		} else if (str_starts_with($substr, 'nine')) {
			$first = 90;
			break;
		}//end if
	}//end for
	// one, two, three, four, five, six, seven, eight, nine
	for ($x = (strlen($line) - 1); $x >= 0; $x--) {
		$substr = substr($line, 0, ($x + 1));
		if (in_array($line[$x], ['1', '2', '3', '4', '5', '6', '7', '8', '9'])) {
			$last = ($line[$x] * 1);
			break;
		} else if (str_ends_with($substr, 'one')) {
			$last = 1;
			break;
		} else if (str_ends_with($substr, 'two')) {
			$last = 2;
			break;
		} else if (str_ends_with($substr, 'three')) {
			$last = 3;
			break;
		} else if (str_ends_with($substr, 'four')) {
			$last = 4;
			break;
		} else if (str_ends_with($substr, 'five')) {
			$last = 5;
			break;
		} else if (str_ends_with($substr, 'six')) {
			$last = 6;
			break;
		} else if (str_ends_with($substr, 'seven')) {
			$last = 7;
			break;
		} else if (str_ends_with($substr, 'eight')) {
			$last = 8;
			break;
		} else if (str_ends_with($substr, 'nine')) {
			$last = 9;
			break;
		}//end if
	}//end for

	$num  = ($first + $last);
	$sum += $num;
	echo "$num: $sum".PHP_EOL;
}//end foreach

var_dump($sum);
