<?php

require 'common.php';

$lines = get_input(15, false);

$boxes = [];
$map = [];
$moves = [];
$pos = [];
foreach ($lines as $y => $line) {
    if (!empty($line) && $line[0] === '#') {
        $map[] = str_split($line);
    } elseif (!empty($line)) {
        $moves[] = $line;
    }

    if (strpos($line, '@')) {
        $pos = [X=>strpos($line, '@'), Y=>$y];
    }
}
v($pos);
board($map);

foreach ($moves as $moveline) {
    foreach(str_split($moveline) as $dir) {
        [$map, $pos] = move($map, $pos, $dir);
        // l($dir);
        // board($map);
    }
}
board($map);
v(sum($map));

function sum($map) {
    $sum = 0;
    foreach($map as $y => $row) {
        foreach($row as $x => $char) {
            if ($char === 'O') {
                $sum += (100 * $y) + $x;
            }
        }
    }
    return $sum;
}
function move($map, $pos, $dir) {
    $dx = $pos[X];
    $dy = $pos[Y];
    $map[$pos[Y]][$pos[X]] = '.';
    switch ($dir) {
        case '<': $dx = $pos[X] - 1; break;
        case '>': $dx = $pos[X] + 1; break;
        case '^': $dy = $pos[Y] - 1; break;
        case 'v': $dy = $pos[Y] + 1; break;
    }

    if ($map[$dy][$dx] === '#') {
        $map[$pos[Y]][$pos[X]] = '@';
        return [$map, $pos];
    } elseif($map[$dy][$dx] === 'O') {
        // Move box
        [$map, $pos] = movebox($map, $pos, $dir);
    } else {
        $pos = [X=>$dx, Y=>$dy];
        $map[$dy][$dx] = '@';
    }

    return [$map, $pos];
}

function movebox($map, $pos, $dir) {
    if ($dir === '>') {
        for($i = $pos[X]+1; $i < count($map[0]); $i++) {
            if ($map[$pos[Y]][$i] === '.') {
                $map[$pos[Y]][$i] = 'O';
                $map[$pos[Y]][$pos[X]+1] = '@';
                $pos = [X=>$pos[X]+1, Y=>$pos[Y]];
                break;
            } elseif ($map[$pos[Y]][$i] === '#') {
                $map[$pos[Y]][$pos[X]] = '@';
                break;
            }
        }
    }
    if ($dir === '<') {
        for($i = $pos[X]-1; $i > 0; $i--) {
            if ($map[$pos[Y]][$i] === '.') {
                $map[$pos[Y]][$i] = 'O';
                $map[$pos[Y]][$pos[X]-1] = '@';
                $pos = [X=>$pos[X]-1, Y=>$pos[Y]];
                break;
            } elseif ($map[$pos[Y]][$i] === '#') {
                $map[$pos[Y]][$pos[X]] = '@';
                break;
            }
        }
    }
    if ($dir === '^') {
        for($i = $pos[Y]-1; $i > 0; $i--) {
            if ($map[$i][$pos[X]] === '.') {
                $map[$i][$pos[X]] = 'O';
                $map[$pos[Y]-1][$pos[X]] = '@';
                $pos = [X=>$pos[X], Y=>$pos[Y]-1];
                break;
            } elseif ($map[$i][$pos[X]] === '#') {
                $map[$pos[Y]][$pos[X]] = '@';
                break;
            }
        }
    }
    if ($dir === 'v') {
        for($i = $pos[Y]+1; $i < count($map); $i++) {
            if ($map[$i][$pos[X]] === '.') {
                $map[$i][$pos[X]] = 'O';
                $map[$pos[Y]+1][$pos[X]] = '@';
                $pos = [X=>$pos[X], Y=>$pos[Y]+1];
                break;
            } elseif ($map[$i][$pos[X]] === '#') {
                $map[$pos[Y]][$pos[X]] = '@';
                break;
            }
        }
    }
    return [$map, $pos];
}
