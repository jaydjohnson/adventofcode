<?php

include 'common.php';

$lines = get_input(2, false);
$sum = 0;
$total_power_sum = 0;
foreach ($lines as $y => $line) {
    $parts = explode(': ', $line);
    $game = explode(' ', $parts[0]);
    $sets = explode('; ', $parts[1]);
    $add_sum = true;
    $fewest = [
        'red' => 0,
        'green' => 0,
        'blue' => 0,
    ];
    foreach( $sets as $set) {
        $totals = [
            'red' => 0,
            'green' => 0,
            'blue' => 0,
        ];
        $cubes = explode(', ', $set);
        var_dump($set);
        foreach($cubes as $cube) {
            $colors = explode(' ', $cube);
            $totals[$colors[1]] += $colors[0];
        }
        foreach(['red', 'green', 'blue'] as $color) {
            if ($totals[$color] > $fewest[$color]) {
                $fewest[$color] = $totals[$color];
            }
        }
        if ($totals['red'] > 12 || $totals['green'] > 13 || $totals['blue'] > 14) {
            $add_sum = false;
        }
    }
    $power = $fewest['red'] * $fewest['green'] * $fewest['blue'];
    var_dump($power);
    //check totals
    if ($add_sum) {
        $sum += $game[1];
    }
    $total_power_sum += $power;
}

var_dump($sum);
var_dump($total_power_sum);