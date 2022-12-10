<?php

global $trees;
$trees = explode( PHP_EOL, file_get_contents( 'day8.in' ));

global $width;
$width = strlen($trees[0]);
global $height;
$height = count($trees);
$found = [];

foreach ($trees as $y => $treerow) {
    $h = 0;
    for ($x = 0; $x < $width; $x++) {
        if ($treerow[$x] > $h || $x == 0) {
            $h = $treerow[$x];
            $found[$y][$x] = $h;
        }
    }

    $h = 0;
    for ($x = $width-1; $x > 0; $x--) {
        if ($treerow[$x] > $h || $x == $width-1) {
            $h = $treerow[$x];
            $found[$y][$x] = $h;
        }
    }
}

for ($x = 0; $x < $width; $x++) {
    $h = 0;
    for ($y = 0; $y < $height; $y++) {
        if ($trees[$y][$x] > $h || $y == 0) {
            $h = $trees[$y][$x];
            $found[$y][$x] = $h;
        }
    }

    $h = 0;
    for ($y = $height-1; $y > 0; $y--) {
        if ($trees[$y][$x] > $h || $y == $height-1) {
            $h = $trees[$y][$x];
            $found[$y][$x] = $h;
        }
    }
}

$tree_sum = 0;
foreach ($found as $f) {
    $tree_sum += count($f);
}

echo $tree_sum . PHP_EOL;
$best_view = 0;
for ($y = 0; $y < $height; $y++) {
    $h = 0;
    for ($x = 0; $x < $width; $x++) {
        $view_distance = get_view_distance($x, $y);
        if ($view_distance > $best_view) {
            $best_view = $view_distance;
        }
    }
}

echo $best_view . PHP_EOL;

get_view_distance(2, 3);

function get_view_distance($xorigin, $yorigin) {
    global $trees;
    global $width;
    global $height;

    $origin_height = $trees[$yorigin][$xorigin];
    // search left
    $left = 0;
    if ($xorigin-1 >= 0) {
        for ($x = $xorigin-1; $x >= 0; $x--) {
            $left++;
            //echo 'checking ' . $trees[$yorigin][$x] . ' vs ' . $origin_height . PHP_EOL;
            if ($trees[$yorigin][$x] >= $origin_height) {
                break;
            }
        }
    }

    $right = 0;
    if ($xorigin+1 <= $width) {
        for ($x = $xorigin+1; $x < $width; $x++) {
            $right++;
            //echo 'checking ' . $trees[$yorigin][$x] . ' vs ' . $origin_height . PHP_EOL;
            if ($trees[$yorigin][$x] >= $origin_height) {
                break;
            }
        }
    }

    // search left
    $up = 0;
    if ($yorigin-1 >= 0) {
        for ($y = $yorigin-1; $y >= 0; $y--) {
            $up++;
            //echo 'checking ' . $trees[$yorigin][$x] . ' vs ' . $origin_height . PHP_EOL;
            if ($trees[$y][$xorigin] >= $origin_height) {
                break;
            }
        }
    }

    $down = 0;
    if ($yorigin+1 <= $height) {
        for ($y = $yorigin+1; $y < $height; $y++) {
            $down++;
            //echo 'checking ' . $trees[$yorigin][$x] . ' vs ' . $origin_height . PHP_EOL;
            if ($trees[$y][$xorigin] >= $origin_height) {
                break;
            }
        }
    }

    return $left * $right * $up * $down;
}
/**
 * 30373
 * 25512
 * 65332
 * 33549
 * 35390
 */
// 3 0 4 3 6 5 7 4 2 3 0 1
// x   x   x   x         
//            x    x   x    x  

    // count ltr save highest postition
    // count rtl to highest position