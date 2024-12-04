<?php

$file = fopen("day18.txt", "r");
$ground = [];

while (($line = fgets($file)) !== false) {
    $ground[] = trim($line);
}

//print_r($ground);
$scores = [];
$repeatingScores = [];
foreach (range(1, 1000) as $minute) {
    $ground = grow($ground);
    $thisScore = getResources($ground, $minute);
    if (!in_array($thisScore, $scores)) {
        $scores[] = $thisScore;
    } else {
        $repeatingScores[] = $thisScore;
    }
}

print_r($repeatingScores);
//getResources($ground);

// echo "2, 1, #: " . countAcres($ground, "#", 2, 1) . PHP_EOL;
// echo "1, 1, #: " . countAcres($ground, "#", 1, 1) . PHP_EOL;
// echo "9, 9, |: " . countAcres($ground, "|", 9, 9) . PHP_EOL;
// echo "0, 7, |: " . countAcres($ground, "|", 0, 7) . PHP_EOL;
// echo "9, 4, #: " . countAcres($ground, "#", 9, 4) . PHP_EOL;

// Part 2 answer = 1000000000 - 433 % 28 = 7
// 433 where patern repeats
// 7+1 element from repeating score is 195952

function grow($ground)
{
    $newGround = $ground;
    foreach ($ground as $y => $line) {
        for ($x = 0; $x < strlen($line); $x++) {
            switch ($line[$x]) {
                case ".":
                    if (countAcres($ground, "|", $x, $y) >= 3) {
                        $newGround[$y][$x] = "|";
                    }
                    break;
                case "|":
                    if (countAcres($ground, "#", $x, $y) >= 3) {
                        $newGround[$y][$x] = "#";
                    }
                    break;
                case "#":
                    if (countAcres($ground, "#", $x, $y) >= 1 && countAcres($ground, "|", $x, $y) >= 1) {
                        $newGround[$y][$x] = "#";
                    } else {
                        $newGround[$y][$x] = ".";
                    }
                    break;
            }
        }
    }
    return $newGround;
}

function countAcres($ground, $type, $x, $y)
{
    $count = 0;
    // Top Left
    if ($y-1 >= 0 && $x-1 >= 0 && $ground[$y-1][$x-1] == $type) {
        $count++;
    }
    // Top Middle
    if ($y-1 >= 0 && $ground[$y-1][$x] == $type) {
        $count++;
    }
    // Top Right
    if ($y-1 >= 0 && $x+1 < strlen($ground[$y]) && $ground[$y-1][$x+1] == $type) {
        $count++;
    }
    // Middle Left
    if ($x-1 >= 0 && $ground[$y][$x-1] == $type) {
        $count++;
    }
    // Middle Right
    if ($x+1 < strlen($ground[$y]) && $ground[$y][$x+1] == $type) {
        $count++;
    }
    // Bottom Left
    if ($y+1 < sizeof($ground) && $x-1 >= 0 && $ground[$y+1][$x-1] == $type) {
        $count++;
    }
    // Bottom Middle
    if ($y+1 < sizeof($ground) && $ground[$y+1][$x] == $type) {
        $count++;
    }
    // Bottom Right
    if ($y+1 < sizeof($ground) && $x+1 < strlen($ground[$y]) && $ground[$y+1][$x+1] == $type) {
        $count++;
    }

    return $count;
}

function getResources($ground, $minute)
{
    $lumberYard = 0;
    $wood = 0;

    foreach ($ground as $y=>$line) {
        for($x = 0; $x < strlen($line); $x++) {
            if ($line[$x] == "#") {
                $lumberYard++;
            }
            if ($line[$x] == "|") {
                $wood++;
            }
        }
    }

    echo "Total Resources $minute: " . ($wood * $lumberYard) . PHP_EOL;
    return $wood * $lumberYard;
}
