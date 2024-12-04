<?php


/*
$file = fopen('day9.txt', 'r');

while (($line = fgets($file)) !== false) {
	$license = preg_split("/ /", $line);
	$meta = get_node($meta, $license);
}

fclose($file);
*/


$players = 405;
$lastMarble = 7095300;
$currentPlayer = 1;
$currentMarble = 0;
$circle = [0];
$scores = array_fill(1, $players, 0);

for($nextMarble = 1; $nextMarble <= $lastMarble; $nextMarble++) {
	// Place marble if not /23
	if ($nextMarble % 23 != 0) {
		//$circle[] = $nextMarble;

		// Add 2 places over or 2nd from start
		if ($currentMarble + 1 == sizeof($circle)) {
			array_splice($circle, 1, 0, $nextMarble);
		} elseif ($currentMarble + 1 > sizeof($circle)) {
			$circle[] = $nextMarble;
		} else {
			array_splice($circle, $currentMarble + 2, 0, $nextMarble);
		}
		$currentMarble = array_search($nextMarble, $circle);
		//echo "size: " . sizeof($circle) . ", cM: " . $currentMarble . PHP_EOL;
	} else {
		// Score for player
		$scores[$currentPlayer] += $nextMarble;
		if ($currentMarble - 7 < 0) {
			$currentMarble = sizeof($circle) - (7 - $currentMarble);
		} else {
			$currentMarble = $currentMarble - 7;
		}
		$scores[$currentPlayer] += $circle[$currentMarble];
		unset($circle[$currentMarble]);
		$circle = array_values($circle);

	}


	//print_circle($circle, $currentMarble, $currentPlayer);
	$currentPlayer = ($currentPlayer + 1 > $players) ? 1 : $currentPlayer+1;
}

asort($scores);
print_r($scores);

function insertMarble($circle, &$players) {
	array_splice($circle, $currentMarble + 1, 0, $nextMarble);
}

function print_circle($circle, $currentMarble, $currentPlayer) {
	echo "[$currentPlayer] ";
	foreach ($circle as $k=>$v) {
		echo $k==$currentMarble ? "($v) " : "$v ";
	}
	echo PHP_EOL;
}

//print_r($circle);