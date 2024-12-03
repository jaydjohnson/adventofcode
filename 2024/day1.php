<?php

include 'common.php';

$lines = get_input(1, false);
$list1 = [];
$list2 = [];
foreach ($lines as $y => $line) {
    $locations = preg_split("/[\s,]+/", $line);
    $list1[] = $locations[0];
    $list2[] = $locations[1];
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
