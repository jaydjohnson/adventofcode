<?php

include 'common.php';

$lines = get_input(4, false);

$board = [];
foreach ($lines as $y => $line) {
    $board[$y] = $line;
}

$count = 0;
$height = count($board);
foreach ($board as $y => $row) {
    $width = strlen($row);
    for ($x = 0; $x < $width; $x++) {
        if ($board[$y][$x] === 'X') {
            foreach ($adjacentdiags as $adjacent) {
                if (inbounds($y + ($adjacent[0] * 3), $x + ($adjacent[1] * 3), $width, $height)) {
                    $letters = $board[$y][$x] . $board[$y + ($adjacent[0] * 1)][$x + ($adjacent[1] * 1)] . $board[$y + ($adjacent[0] * 2)][$x + ($adjacent[1] * 2)] . $board[$y + ($adjacent[0] * 3)][$x + ($adjacent[1] * 3)];
                    if ($letters === 'XMAS' || $letters === "SAMX") {
                        $count++;
                    }
                }
            }
        }
    }
}

v($count);

$count = 0;
foreach ($board as $y => $row) {
    $width = strlen($row);
    for ($x = 0; $x < $width; $x++) {
        if ($board[$y][$x] === 'A') {
            if (inbounds($y + 1, $x + 1, $width, $height) && inbounds($y - 1, $x - 1, $width, $height)) {
                $letters1 = $board[$y-1][$x-1] . 'A' . $board[$y+1][$x+1];
                $letters2 = $board[$y-1][$x+1] . 'A' . $board[$y+1][$x-1];
                if (($letters1 === 'MAS' || $letters1 === "SAM") && ($letters2 === 'MAS' || $letters2 === 'SAM')) {
                    $count++;
                }
            }
        }
    }
}
v($count);
