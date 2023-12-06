<?php

include 'common.php';

$lines = get_input(6, false);

$times = explode(' ', explode(':', $lines[0])[1]);
$distances = explode(' ', explode(':', $lines[1])[1]);
$times = array_values(array_filter($times));
$distances = array_values(array_filter($distances));

$total_times_beat = 1;
foreach ($times as $i => $time) {
    $hold = 1;
    $times_beat=0;
    while ($hold < $time) {
        $distance = ($time - $hold) * $hold;
        $hold++;
        if ($distance > $distances[$i]) {
            $times_beat++;
        }
    }
    $total_times_beat *= $times_beat;
}
d($total_times_beat);

$time = implode('', $times);
$distance = implode('', $distances);

$total_times_beat = 1;
$hold = 1;
$times_beat=0;
while ($hold < $time) {
    $current_distance = ($time - $hold) * $hold;
    $hold++;
    if ($current_distance > $distance) {
        $times_beat++;
    }
}
$total_times_beat *= $times_beat;

d($total_times_beat);