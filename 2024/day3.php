<?php

include 'common.php';

$lines = get_input(3, false);

$sum = 0;
$instructions = [];
foreach ($lines as $y => $line) {
    preg_match_all('/mul\((\d{1,3},\d{1,3})\)/', $line, $matches);
    $instructions = array_merge($instructions, $matches[1]);
}
$sum = 0;
foreach ($instructions as $match) {
    $nums = explode(',', $match);
    $sum += $nums[0] * $nums[1];
}

d($sum);

$sum = 0;
$instructions = [];
foreach ($lines as $y => $line) {
    //$line = preg_replace("/don't\(\).*?do\(\)/", '', $line);
    preg_match_all("/don't\(\)|do\(\)|mul\((\d{1,3},\d{1,3})\)/", $line, $matches);
    $instructions = array_merge($instructions, $matches);
}
$sum = 0;
d($instructions);
$on = true;
foreach ($instructions[0] as $i => $instruction) {
    if (str_starts_with($instruction, "don't()")) {
        $on = false;
    } elseif (str_starts_with($instruction, 'do()')) {
        $on = true;
    } elseif ($on) {
        $nums = explode(',', $instructions[1][$i]);
        $sum += $nums[0] * $nums[1];
    }
}
d($sum);

// 59238634 - Low
// 85508223
// 87519257 - High
