<?php
include(__DIR__.'/utils.php');

gc_disable();
ini_set('memory_limit', '1024M');

//$file = fopen('day10.txt', 'r');
$powerGrid = array_fill(0, 300, array_fill(0, 300, 0));
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


printf("Precalculating isums for serial: %d ...\n", $serialNumber);
$isums = $powerGrid; 
for($y=1;$y<=300;$y++) {
	for($x=1;$x<=300;$x++) {
		$isums[$y][$x] = $isums[$y][$x] + $isums[$y-1][$x] + $isums[$y][$x-1] - $isums[$y-1][$x-1];
	}
}
printf("Precalculating isums for serial: %d ... done\n", $serialNumber);


$highestSize = 1;
$highestPower = 0;
$winningX = 0;
$winningY = 0;

//print_r($powerGrid);
for($s=1; $s <= 300; $s++) {
	for($x = 1; $x <= 300 - $s; $x++) {
		$xs = $x + $s;
		for ($y = 1; $y <= 300 - $s; $y++) {
			$ys = $y + $s;
			$sum = 0;

			$sum = $isums[$ys][$xs] - $isums[$y][$xs] - $isums[$ys][$x] + $isums[$y][$x];
			// Sum square
			// for($sx = 0; $sx < $s; $sx++) {
			// 	echo ".";
			// 	for($sy = 0; $sy < $s; $sy++) {
			// 		$sum += $powerGrid[$y+$sy][$x+$sx];
			// 	}
			// }

			if ($sum > $highestPower) {
				$highestPower = $sum;
				$winningY = $y+1;
				$winningX = $x+1;
				$highestSize = $s;
			}
		}
	}
	echo "size: $s... $winningX, $winningY, $highestSize: $highestPower" . PHP_EOL;
}

echo "$winningX, $winningY, $highestSize: $highestPower" . PHP_EOL;

