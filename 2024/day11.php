<?php

require 'common.php';

$lines = get_input(11, false);

$stones = explode(' ', $lines[0]);
v($stones);

for ($i=0; $i < 25; $i++) {
    l($i . ' ' . count($stones));
    $stones = blink($stones);
    //l($stones, ' ');
}
v(count($stones));

$stones = explode(' ', $lines[0]);
$sum = 0;
foreach ($stones as $stone) {
    $sum += blinkr($stone, 75);
}
v($sum);

function blink($stones) {
    $new_stones = [];

    foreach ($stones as $stone) {
        if ($stone == 0) {
            $new_stones[] = "1";
        } elseif (strlen($stone) % 2 == 0) {
            $parts = str_split($stone, strlen($stone) / 2);
            $new_stones[] = $parts[0];
            $new_stones[] = strval(intval($parts[1]));
        } else {
            $new_stones[] = strval(intval($stone) * 2024);
        }
    }

    return $new_stones;
}

function blinkr($stone, $blinks_left) {
    if ($blinks_left == 0) {
        return 1;
    }

    if ($stone == 0) {
        return blinkr(1, $blinks_left - 1);
    }
    if (strlen($stone) % 2 == 0) {
            $parts = str_split($stone, strlen($stone) / 2);
            $left = blinkr($parts[0], $blinks_left - 1);
            $right = blinkr(strval(intval($parts[1])), $blinks_left - 1);
            return $left + $right;
    }

    return blinkr(strval(intval($stone) * 2024), $blinks_left - 1);
}