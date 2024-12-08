<?php

include 'common.php';

$lines = get_input(7, false);

$sum = 0;

foreach ($lines as $y => $line) {
    $parts = explode(": ", $line);
    $target = $parts[0];
    $nums = explode(" ", $parts[1]);
    $ops = ['+', '*'];
    $op_positions = array_fill(0, count($nums)-1, $ops);
    $str = "";
    $combos = array_fill(1, count($op_positions) * count($ops), '');
    $canidates = [$nums[0]];
    for($i = 1; $i < count($nums); $i++) {
        $new_canidates = [];
        foreach ($canidates as $canidate) {
            if ($canidate > $target) {
                continue;
            }
            $new_canidates[] = $nums[$i] + $canidate;
            $new_canidates[] = $nums[$i] * $canidate;
            // Part 2
            $new_canidates[] = $canidate . $nums[$i];
        }
        $canidates = $new_canidates;
    }
    if (in_array($target, $canidates)) {
        $sum += $target;
    }
}

v($sum);

