<?php

$fabric = array_fill(1, 1000, array_fill(1, 1000, '.'));

$file = fopen('day3.txt', 'r');
$claimed = 0;
while (($line = fgets($file)) !== false) {
	preg_match('/#(\d+) @ (\d+),(\d+): (\d+)x(\d+)/', $line, $matches);
	fillFabric($matches, $fabric, $claimed);
	//echo "$line" . PHP_EOL;
	//print_r($fabric);
	// echo $line . ($found2 ? ' 2s' : '') .  ($found3 ? ' 3s' : '') . PHP_EOL;
}

echo "total claimed: $claimed" . PHP_EOL;

fclose($file);

function fillFabric($matches, &$fabric, &$claimed) {
	$owner = $matches[1];
	$xstart = $matches[2]+1;
	$ystart = $matches[3]+1;
	$width = $matches[4];
	$height = $matches[5];

	for($x = $xstart; $x < $xstart+$width; $x++) {
		for ($y = $ystart; $y < $ystart+$height; $y++) {
			if ($fabric[$x][$y] == '.') {
				$fabric[$x][$y] = $owner;
			} elseif ($fabric[$x][$y] !== 'X') {
				$fabric[$x][$y] = 'X';
				$claimed++;
			}
		}
	}
}