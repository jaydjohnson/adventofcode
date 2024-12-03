<?php

include 'common.php';

$lines = get_input(2, false);

foreach ($lines as $y => $line) {
    $levels = explode(' ', $line);
    $dir = '';
    $current = $levels[0];
    for ($i = 1; $i < count($levels); $i++) {
        $diff = $current - $levels[$i];
        if ($diff > 0) {
            $dir = 'desc';
        } else {
            $dir = 'asc';
        }
    }
}
sort($list1);
sort($list2);

$diff = 0;
foreach ($list1 as $i => $item) {
    $diff += abs($item - $list2[$i]);
}

$list2_count = array_count_values($list2);
$sim = 0;
foreach ($list1 as $i => $item) {
    if (isset($list2_count[$item])) {
        $sim += $item * $list2_count[$item];
    }
}
var_dump($diff);
var_dump($sim);
