<?php

include 'common.php';

$lines = get_input(8, true);

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
$board2 = $board;
foreach($ants as $ant) {
    v($ant);
    foreach ($ant as $i => $ant1) {
        for($j = $i+1; $j < count($ant); $j++) {
            $ant2 = $ant[$j];
            $dy = $ant1[Y] - $ant2[Y];
            $dx = $ant1[X] - $ant2[X];
            echo $dy . ', ' . $dx . PHP_EOL;

            // dir1
            if(inbounds($ant1[Y] + $dy, $ant1[X] + $dx, strlen($board[0]), count($board))) {
                if ($board[$ant1[Y] + $dy][$ant1[X] + $dx] !== '#') {
                    $count++;
                    $board[$ant1[Y] + $dy][$ant1[X] + $dx] = '#';
                }
            }

            // dir2
            if(inbounds($ant2[Y] - $dy, $ant2[X] - $dx, strlen($board[0]), count($board))) {
                if ($board[$ant2[Y] - $dy][$ant2[X] - $dx] !== '#') {
                    $count++;
                    $board[$ant2[Y] - $dy][$ant2[X] - $dx] = '#';
                }
            }
        }
    }
    board($board);
}

v($count);
$count = 0;
$board = $board2;
foreach($ants as $ant) {
    v($ant);
    foreach ($ant as $i => $ant1) {
        for($j = $i+1; $j < count($ant); $j++) {
            $ant2 = $ant[$j];
            $dy = $ant1[Y] - $ant2[Y];
            $dx = $ant1[X] - $ant2[X];
            echo $dy . ', ' . $dx . PHP_EOL;

            // dir1
            $pos = $ant1;
            while(inbounds($pos[Y] + $dy, $pos[X] + $dx, strlen($board[0]), count($board))) {
                if ($board[$pos[Y] + $dy][$pos[X] + $dx] !== '#') {
                    $count++;
                    $board[$pos[Y] + $dy][$pos[X] + $dx] = '#';
                }
                $pos = [Y => $pos[Y] + $dy, X => $pos[X] + $dx];
            }

            // dir2
            $pos = $ant2;
            while(inbounds($pos[Y] - $dy, $pos[X] - $dx, strlen($board[0]), count($board))) {
                if ($board[$pos[Y] - $dy][$pos[X] - $dx] !== '#') {
                    $count++;
                    $board[$pos[Y] - $dy][$pos[X] - $dx] = '#';
                }
                $pos = [Y => $pos[Y] + $dy, X => $pos[X] + $dx];
            }
        }
    }
    board($board);
}

v($count);