<?php

$file = fopen('day13.txt', 'r');
$track = [];
$trains = [];
$x = 0;
$y = 0;
while(($line = fgets($file)) !== false) {
	$track[] = array_fill(0, 13, ' ');
	for($x = 0; $x < strlen($line) - 1; $x++) {
		switch ($line[$x]) {
			case '>':
				$trains[] = new Train($x, $y, 'E');
				$track[$y][$x] = '-';
				break;
			case '<':
				$trains[] = new Train($x, $y, 'W');
				$track[$y][$x] = '-';
				break;
			case '^':
				$trains[] = new Train($x, $y, 'N');
				$track[$y][$x] = '|';
				break;
			case 'v':
				$trains[] = new Train($x, $y, 'S');
				$track[$y][$x] = '|';
				break;
			case "\r":
				break;
			case "\n":
				break;
			default:
				$track[$y][$x] = $line[$x];
				break;
		}
	}
	$y++;
}
//quickDraw($track);
print_r($trains);
do {
	moveTrains($track, $trains);
	reorderTrains($trains);
	//print_r($trains);
	// drawTrackTrain($track, $trains);
} while (sizeof($trains) > 1);
print_r($trains);
fclose($file);

function reorderTrains(&$trains) {
	usort($trains, function($a, $b) {
		return ($a->y < $b->y || ($a->y == $b->y && $a->x < $b->x)) ? -1 : 1;
	});
}

function moveTrains($track, &$trains) {
	foreach ($trains as $train) {
		// get direction and look at next train track
		switch ($train->direction) {
			case "N":
				$nextTrack = $track[$train->y - 1][$train->x];
				$train->y--;
				switch ($nextTrack) {
					case "/":
						$train->direction = "E";
						break;
					case "\\":
						$train->direction = "W";
						break;
					case "+":
						$train->getNewDirection();
						break;
				}
				break;
			case "E":
				$nextTrack = $track[$train->y][$train->x + 1];
				$train->x++;
				switch ($nextTrack) {
					case "/":
						$train->direction = "N";
						break;
					case "\\":
						$train->direction = "S";
						break;
					case "+":
						$train->getNewDirection();
						break;
				}
				break;
			case "S":
				$nextTrack = $track[$train->y + 1][$train->x];
				$train->y++;
				switch ($nextTrack) {
					case "/":
						$train->direction = "W";
						break;
					case "\\":
						$train->direction = "E";
						break;
					case "+":
						$train->getNewDirection();
						break;
				}
				break;
			case "W":
				$nextTrack = $track[$train->y][$train->x - 1];
				$train->x--;
				switch ($nextTrack) {
					case "/":
						$train->direction = "S";
						break;
					case "\\":
						$train->direction = "N";
						break;
					case "+":
						$train->getNewDirection();
						break;
				}
				break;
		}
		if (checkCollisions($trains)) {
			echo "Collided: " . $train->x . ', ' . $train->y . PHP_EOL;
			//exit;
		}
	}
}

function checkCollisions(&$trains) {
	foreach ($trains as $k => $train) {
		$trainsCopy = $trains;
		//for ($i = $k+1; $i < sizeof($trains); $i++) {
		foreach ($trainsCopy as $k2 => $nextTrain) {
			if ($k == $k2) {
				break;
			}
			if ($train->x == $nextTrain->x && $train->y == $nextTrain->y) {
				unset($trains[$k]);
				unset($trains[$k2]);
				echo "Collided: " . $train->x . ', ' . $train->y . PHP_EOL;
				//$trains = array_values($trains);
				//return true;
			}
		}
	}
	$trains = array_values($trains);
	return false;
}

function quickDraw($track) {
	foreach($track as $y => $line) {
		//print_r($line);
		echo implode('', $line) . PHP_EOL;
	}
}

function drawTrackTrain($track, $trains){
	foreach($track as $y => $line) {
		foreach ($line as $x => $char) {
			$trainFound = false;
			foreach ($trains as $train) {
				if ($train->x == $x and $train->y == $y) {
					echo "T";
					$trainFound = true;
				}
			}
			if (! $trainFound) {
				echo $track[$y][$x];
			}
		}
		echo PHP_EOL;
	}
}

class Train {
	public $x;
	public $y;
	public $direction;
	public $nextTurn = 'L';

	public function __construct($x, $y, $direction) {
		$this->x = $x;
		$this->y = $y;
		$this->direction = $direction;
	}

	public function getNewDirection() {
		$turn = $this->nextTurn;
		$direction = "";
		switch ($this->nextTurn) {
			case "L":
				$this->nextTurn = "S";
				switch ($this->direction) {
					case "N": $direction = "W"; break;
					case "E": $direction = "N"; break;
					case "S": $direction = "E"; break;
					case "W": $direction = "S"; break;
				}
				break;
			case "S":
				$this->nextTurn = "R";
				$direction = $this->direction;
				break;
			case "R":
				$this->nextTurn = "L";
				switch ($this->direction) {
					case "N": $direction = "E"; break;
					case "E": $direction = "S"; break;
					case "S": $direction = "W"; break;
					case "W": $direction = "N"; break;
				}
				break;
		}
		$this->direction = $direction;
		return $direction;
	}
}