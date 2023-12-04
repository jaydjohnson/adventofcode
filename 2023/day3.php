<?php

include 'common.php';

$lines = get_input(3, false);

$grid = [];
$x = 0;
$is_adding = false;
$new_num = '';
$part_sum = 0;
$should_include = false;
$gears = [];
$should_add_to_gear = false;
// create grid
foreach ($lines as $y => $line) {
    for ($x=0; $x < strlen($line); $x++) {
        $grid[$y][$x] = $line[$x];
    }
}

foreach ($lines as $y => $line) {
    for ($x=0; $x < strlen($line); $x++) {

        if (in_array($line[$x], range('0', '9'))) {
            $is_adding = true;
            $new_num .= $line[$x];
            //$should_include = false;
            // check if should include
            foreach ($adjacentdiags as $set) {
                $ydiff = $set[1];
                $xdiff = $set[0];
                if (inbounds($x+$xdiff, $y+$ydiff, strlen($line), sizeof($grid))) {
                    if (!in_array($grid[$y+$ydiff][$x+$xdiff], ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '.'])) {
                        // echo 'found symbol on current num: ' . $new_num . ' direction: ' . $xdiff . ' ' . $ydiff .  PHP_EOL;
                        $should_include = true;
                    }
                    if ($grid[$y+$ydiff][$x+$xdiff] === '*') {
                        // found gear - we should save this full number into gear data
                        $geary = $y+$ydiff;
                        $gearx = $x+$xdiff;
                        $current_gear_position = "$geary$gearx";
                        $should_add_to_gear = true;
                    }
                }
            }
        } else {
            if ($is_adding) {
                if ($should_include) {
                    $part_sum += (int)$new_num;
                    echo "Adding new part number: $new_num" . PHP_EOL;
                }
                if ($should_add_to_gear) {
                    $gears[$current_gear_position][] = $new_num;
                    echo "Adding potential gear: $new_num" . PHP_EOL;
                }
            }
            $new_num = '';
            $is_adding = false;
            $should_include = false;
            $should_add_to_gear = false;
            
        }
    }
}

// Part 1 answer
var_dump($part_sum);
$ratio_sum = 0;
foreach ($gears as $gear) {
    if (sizeof($gear) > 1) {
        $ratio_sum += $gear[0] * $gear[1];
    }
}
// Part 2 answer
var_dump($ratio_sum);
