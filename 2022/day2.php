<?php

/**
 * a - rock -
 * b - paper - 
 * c - scissors - 
 * y - paper - 2
 * x - rock - 1
 * z - scissors - 3
 * X - lose
 * y - draw
 * z - win
 * lose - 0
 * draw - 3
 * win - 6
 */
$strat1_scores = [
    'Y' => [ 'A' => 8, 'B' => 5, 'C' => 2],
    'X' => [ 'A' => 4, 'B' => 1, 'C' => 7],
    'Z' => [ 'A' => 3, 'B' => 9, 'C' => 6],
];

$strat2_scores = [
    'X' => [ 'A' => 3, 'B' => 1, 'C' => 2],
    'Y' => [ 'A' => 4, 'B' => 5, 'C' => 6],
    'Z' => [ 'A' => 8, 'B' => 9, 'C' => 7],
];

$strat1_score = 0;
$strat2_score = 0;

$handle = fopen("day2-input.txt", "r");

if ($handle) {
    while (($line = fgets($handle)) !== false) {
        $opponent = $line[0];
        $you = $line[2];
        $strat1_score += $strat1_scores[$you][$opponent];
        $strat2_score += $strat2_scores[$you][$opponent];
    }
    echo $strat1_score . PHP_EOL;
    echo $strat2_score . PHP_EOL;
    fclose($handle);
}