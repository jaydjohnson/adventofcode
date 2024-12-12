<?php

require 'common.php';

$lines = get_input(12, true);

$plots = [];
foreach ($lines as $y => $line) {
    $plots[] = str_split($line);
}

// Find all letters of the same.  
// if letter found add 1 to area
// If letter not in specific direction add 1 to param
// 
board($plots);
$ori_plots = $plots;
$price = 0;
foreach($plots as $y => $plot) {
    for($x = 0; $x < count($plot); $x++) {
        $char = $plots[$y][$x];
        if ($char !== '.') {
            [$plots, $area, $perim] = search([Y => $y, X => $x], $char, $plots, 0, 0);
            l("char: $char\narea: $area\nperim: $perim");
            $price += $area * $perim;
        }
    }
}
l("total: $price");

function search($pos, $char, $plots, $area, $perim) {
    // mark current spot as found
    $plots[$pos[Y]][$pos[X]] = '.';
    $area++;
    global $adjacents;
    foreach($adjacents as $dir) {
        if (inbounds($pos[Y] + $dir[0], $pos[X] + $dir[1], count($plots[0]), count($plots))) {
            if ($plots[$pos[Y] + $dir[0]][$pos[X] + $dir[1]] === $char) {
                // continue searching in next plot
                [$plots, $area, $perim] = search([Y => $pos[Y] + $dir[0], X => $pos[X] + $dir[1]], $char, $plots, $area, $perim);
            } elseif ($plots[$pos[Y] + $dir[0]][$pos[X] + $dir[1]] !== '.') {
                $perim++;
            }
        } else {
            $perim++;
        }
    }
    return [$plots, $area, $perim];
}

board($plots);