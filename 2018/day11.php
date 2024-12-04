<?php
include(__DIR__.'/utils.php');

gc_disable();
ini_set('memory_limit', '1024M');

//$file = fopen('day10.txt', 'r');
$powerGrid = array_fill(1, 300, array_fill(1, 300, 0));
$totalPower = array_fill(1, 298, array_fill(1, 298, 0));
$serialNumber = 2568;

foreach ($powerGrid as $y=>$row) {
	foreach ($row as $x=>$v) {
		$rackId = $x + 10;
		$powerLevel = $rackId * $y;
		$powerLevel += $serialNumber;
		$powerLevel = $powerLevel * $rackId;
		if ($powerLevel < 100) {
			$powerLevel = 0;
		} else {
			$pls = (string)$powerLevel;
			$powerLevel = (int)$pls[strlen($pls)-3];
		}
		$powerLevel -= 5;
		//echo "$x, $y, $powerLevel" . PHP_EOL;
		$powerGrid[$y][$x] = $powerLevel;
	}
}

$highestPower = 0;
$winningX = 0;
$winningY = 0;

//print_r($powerGrid);

foreach($totalPower as $y=>$row) {
	foreach ($row as $x=>$v) {
		$sum =  $powerGrid[$y][$x] +
				$powerGrid[$y][$x+1] +
				$powerGrid[$y][$x+2] +
				$powerGrid[$y+1][$x] +
				$powerGrid[$y+1][$x+1] +
				$powerGrid[$y+1][$x+2] +
				$powerGrid[$y+2][$x] +
				$powerGrid[$y+2][$x+1] +
				$powerGrid[$y+2][$x+2];
		$v = $sum;

		if ($sum > $highestPower) {
			$highestPower = $sum;
			$winningY = $y;
			$winningX = $x;
		}
	}
}

echo "$winningX, $winningY: $highestPower" . PHP_EOL;

