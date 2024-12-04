<?php

$sum = 0;
$containers = [];
$twos = 0;
$threes = 0;

$file = fopen('day2.txt', 'r');
while (($line = fgets($file)) !== false) {
	$line = trim($line);
	$containers[] = $line;

	checkDiff($line, $containers);
}


fclose($file);

function checkDiff($line, $containers) {
	$line_chars = preg_split('//', $line);
	foreach ($containers as $container) {
		$diff = 0;	
		$container_chars = preg_split('//', $container);
		foreach($line_chars as $k=>$line_char) {
			// print_r($line_chars);
			// print_r($container_chars);
			if ($line_char <> $container_chars[$k]) {
				$diff++;
			}
		}
		if ($diff == 1) {
			echo 'Found 1 diff: ' . PHP_EOL . $line . PHP_EOL . $container;
		} else {
			//echo "Diff: $diff" . PHP_EOL . $line . PHP_EOL . $container . PHP_EOL;
		}
	}
}

nvosmkcdtdbfhyxsphzgraljq
nvosmkcdtdbfhyxsphzgraljq