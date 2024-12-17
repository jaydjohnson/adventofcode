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

$checked = [];
// [$checked, $area, $perim] = search([Y => 0, X => 0], 'A', $checked, 0, 0);
// vd ($checked);

$ori_plots = $plots;
$price = 0;
foreach($plots as $y => $plot) {
    for($x = 0; $x < count($plot); $x++) {
        $char = $plots[$y][$x];
        if (!in_array("$y,$x", $checked)) {
            [$checked, $area, $perim] = search([Y => $y, X => $x], $char, $checked, 0, 0);
            l("char: $char\narea: $area\nperim: $perim");
            $price += $area * $perim;
        }
    }
}
l("total: $price");

function search($pos, $char, $checked, $area, $perim) {
    global $plots;
    global $adjacents;
    // mark current spot as found
    $checked[] = "{$pos[Y]},{$pos[X]}";
    $area++;
    foreach($adjacents as $dir) {
        $dy = $pos[Y] + $dir[0];
        $dx = $pos[X] + $dir[1];
        if (inbounds($dy, $dx, count($plots[0]), count($plots))) {
            if (!in_array("$dy,$dx", $checked) && $plots[$dy][$dx] === $char) {
                // continue searching in next plot
                [$checked, $area, $perim] = search([Y => $dy, X => $dx], $char, $checked, $area, $perim);
            } elseif ($plots[$dy][$dx] !== $char) {
                $perim++;
            }
        } else {
            $perim++;
        }
    }
    return [$checked, $area, $perim];
}
