<?php

include 'common.php';

$lines = get_input(8, false);

$count = 0;
$board = [];
const X = 'x';
const Y = 'y';
foreach ($lines as $y => $line) {
    $board[] = $line;
    foreach (str_split($line) as $x => $char) {
        if ($char !== '.') {
            $ants[$char][] = [Y => $y, X => $x];
        }
    }
}

$locations = [];

d($ants);
board($board);
foreach($ants as $ant) {
    v($ant);
    foreach ($ant as $i => $ant1) {
        for($j = $i+1; $j < count($ant); $j++) {
            $ant2 = $ant[$j];
            $dy = abs($ant1[Y] - $ant2[Y]);
            $dx = abs($ant1[X] - $ant2[X]);
            echo $dy . ', ' . $dx . PHP_EOL;

            // dir1
            $ny = $ant1[Y] + ($ant1[Y] < $ant2[Y] ? -$dy : $dy);
            $nx = $ant1[X] + ($ant1[X] < $ant2[X] ? -$dx : $dx);
            if(inbounds($ny, $nx, strlen($board[0]), count($board))) {
                if ($board[$ny][$nx] !== '#') {
                    $count++;
                    $board[$ny][$nx] = '#';
                }
            }

            // dir2
            $ny = $ant2[Y] + ($ant1[Y] > $ant2[Y] ? -$dy : $dy);
            $nx = $ant2[X] + ($ant1[X] > $ant2[X] ? -$dx : $dx);
            if(inbounds($ny, $nx, strlen($board[0]), count($board))) {
                if ($board[$ny][$nx] !== '#') {
                    $count++;
                    $board[$ny][$nx] = '#';
                }
            }
        }
    }
    board($board);
}

v($count);
