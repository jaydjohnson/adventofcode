<?php

require 'common.php';

$lines = get_input(15, false);

$hashes = explode(',', $lines[0]);

function h($str) {
    $current_value = 0;
    foreach (str_split($str) as $ch) {
        $current_value += ord($ch);
        $current_value = $current_value * 17;
        $current_value = $current_value % 256;
    }
    return $current_value;
}

$sum = 0;
$boxes = array_fill(0, 255, []);

foreach ($hashes as $hash) {
    $current_value = 0;
    $current_value = h($hash);
    echo "$hash = $current_value" . PHP_EOL;
    if (str_contains($hash, '=')) {
        // do equal stuff
        [$label, $focal_length] = explode('=', $hash);
        $box = h($label);
        if (! empty(array_column($boxes[$box], $label))) {
            foreach ($boxes[$box] as $k => $lens) {
                if (isset($lens[$label])) {
                    $boxes[$box][$k][$label] = (int) $focal_length;
                }
            }
        } else {
            $boxes[$box][] = [$label => (int) $focal_length];
        }
    }
    if (str_contains($hash, '-')) {
        // do minus stuff
        [$label, $focal_length] = explode('-', $hash);
        $box = h($label);
        if (! empty(array_column($boxes[$box], $label))) {
            foreach ($boxes[$box] as $k => $lens) {
                if (isset($lens[$label])) {
                    unset($boxes[$box][$k]);
                    $boxes[$box] = array_values($boxes[$box]);
                }
            }
        }
    }
    d(array_filter($boxes));
    $sum += $current_value;
}
d($sum);

$fp = 0;

foreach ($boxes as $i => $box) {

    foreach ($box as $j => $focal_length) {
        $fp += ($i + 1) * ($j + 1) * array_values($focal_length)[0];
    }
}
d($fp);
