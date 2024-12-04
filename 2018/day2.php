<?php

$sum = 0;
$containers = [];
$twos = 0;
$threes = 0;

$file = fopen('day2.txt', 'r');
while (($line = fgets($file)) !== false) {
	$found2 = false;
	$found3 = false;

	$line = sortLine($line);
	foreach (count_chars($line, 1) as $i => $val) {
		if ($val == 2 && ! $found2) {
			$found2 = true;
			$twos++;
		}
		if ($val == 3 && ! $found3) {
			$found3 = true;
			$threes++;
		}
	}

	echo $line . ($found2 ? ' 2s' : '') .  ($found3 ? ' 3s' : '') . PHP_EOL;
}

echo "$twos x $threes = " . ($twos * $threes) . PHP_EOL;

fclose($file);

function sortLine($line) {
	$characters = preg_split('//', $line);
	sort($characters);
	return implode($characters);
}

function countCharacters($line) {

}
