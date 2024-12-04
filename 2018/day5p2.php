<?php


$file = fopen('day5.txt', 'r');
global $letters;

for ($x = 1; $x <= 26; $x++) {
	$letters['upper'][$x] = chr($x+64);
	$letters['lower'][$x] = chr($x+96);
}

while (($line = fgets($file)) !== false) {
	$polymer = $line;
}

echo "original size: " . strlen($polymer) . PHP_EOL;

removeUnits($polymer);

function removeUnits($polymer) {
	global $letters;
	
	$originalPolymer = $polymer;
	for ($x = 1; $x <= 26; $x++) {
		$l = $letters['lower'][$x];
		$u = $letters['upper'][$x];

		echo "Removing $l/$u units: ";
		$polymer = preg_replace("/$u|$l/", '', $originalPolymer);

		$polymer = react($polymer);
		echo strlen($polymer) . PHP_EOL;
	}
}

function react($polymer) {
	global $letters;

	$original_length = strlen($polymer);
	for ($x = 1; $x <= 26; $x++) {
		$l = $letters['lower'][$x];
		$u = $letters['upper'][$x];
		$polymer = preg_replace("/$l$u|$u$l/", '', $polymer);
		

	}
	// echo "$l$u : " . $polymer . PHP_EOL;
		if ($original_length == strlen($polymer)) {
			return $polymer;
		} else {
			return react($polymer);
		}
}

fclose($file);