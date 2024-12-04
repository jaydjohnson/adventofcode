<?php


$sum = 0;
$frequencies = [];
$found = false;
while ($found == false) {

	$file = fopen('day1.txt', 'r');
	while (($line = fgets($file)) !== false && ! $found) {
		$change = (int) $line;
		//echo $sum . " += " . $change;
		$sum += (int) $change;
		//echo " = $sum" . PHP_EOL;
		if ($sum <> $change && in_array($sum, $frequencies)) {
			$found = true;
			echo "$sum.";
		}
		$frequencies[] = $sum;
	}
	//sort($frequencies);

	fclose($file);
}

