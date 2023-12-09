<?php

require 'common.php';

$lines = get_input(9, false);

$sequences = [];
foreach ($lines as $line) {
    $sequences[] = explode(' ', $line);
}

$sums = 0;
foreach ($sequences as $sequence) {
    $diffs = [];
    $current_seq = $sequence;
    do {
        $current_diffs = [];
        for ($i=0; $i<sizeof($current_seq)-1; $i++) {
            $current_diffs[] = $current_seq[$i+1] - $current_seq[$i];
        }
        $current_seq = $current_diffs;
        $diffs[] = $current_diffs;
        $all_zeroes = count(array_unique($current_diffs)) === 1 && $current_diffs[0] === 0;

    } while (! $all_zeroes);

    $next_level_diff = 0;
    for ($i = sizeof($diffs); $i > 0; $i--) {
        $current_diff = $diffs[$i-1];
        $current_diff[] = $current_diff[count($current_diff)-1] + $next_level_diff;
        $next_level_diff = $current_diff[count($current_diff)-1];
    }
    $next_num = $sequence[count($sequence)-1] + $next_level_diff;
    $sums += $next_num;
}

d($sums);

$sums = 0;
foreach ($sequences as $sequence) {
    $diffs = [];
    $current_seq = $sequence;
    do {
        $current_diffs = [];
        for ($i=0; $i<sizeof($current_seq)-1; $i++) {
            $current_diffs[] = $current_seq[$i+1] - $current_seq[$i];
        }
        $current_seq = $current_diffs;
        $diffs[] = $current_diffs;
        $all_zeroes = count(array_unique($current_diffs)) === 1 && $current_diffs[0] === 0;

    } while (! $all_zeroes);

    $next_level_diff = 0;
    for ($i = sizeof($diffs); $i > 0; $i--) {
        $current_diff = $diffs[$i-1];
        array_unshift($current_diff, $current_diff[0] - $next_level_diff);
        $next_level_diff = $current_diff[0];
    }
    $next_num = $sequence[0] - $next_level_diff;
    $sums += $next_num;
}

d($sums);