<?php

require 'common.php';

$lines = get_input(18, false);
$directions = [];
$meters = [];
$colors = [];
$directions = ['U' => [0, -1], 'D' => [0, 1], 'L' => [-1, 0], 'R' => [1, 0]];
$points = [[0, 0,]];
$boundries = 0;

foreach ($lines as $line) {
    [$d, $m, $color] = explode(' ', $line);
    [$dc, $dr] = $directions[$d];
    $distance = (int) $m;
    $boundries += $distance;
    [$c, $r] = end($points);
    $points[] = [$c + $dc * $distance, $r + $dr * $distance];
}

// shoelace formula
$area = 0;
for ($i = 1; $i < count($points); $i++) {
    $area += $points[$i][1] * ($points[$i - 1][0] - $points[($i + 1) % count($points)][0]);
}
d(abs($area) / 2);
d($boundries);
$interior = (abs($area) / 2) - $boundries / 2 + 1;
// picks theorm

d($interior + $boundries);

// part 2
$points = [[0, 0,]];
$boundries = 0;

foreach ($lines as $line) {
    [$d, $m, $color] = explode(' ', $line);
    //[$dc, $dr] = $directions[$d];
    $color = substr($color, 2, -1);
    $dir = (int) substr($color, -1);
    [$dc, $dr] = $directions["RDLU"[$dir]];
    $distance = base_convert(substr($color, 0, -1), 16, 10);
    $boundries += $distance;
    [$c, $r] = end($points);
    $points[] = [$c + $dc * $distance, $r + $dr * $distance];
}

// shoelace formula
$area = 0;
for ($i = 1; $i < count($points); $i++) {
    $area += $points[$i][1] * ($points[$i - 1][0] - $points[($i + 1) % count($points)][0]);
}
d(abs($area) / 2);
d($boundries);
$interior = (abs($area) / 2) - $boundries / 2 + 1;
// picks theorm

d($interior + $boundries);