<?php

$lines = explode( PHP_EOL, file_get_contents( 'day13.in' ));

$group = 1;
$sum_group = 0;
for ($i = 0; $i < count($lines); $i+=3) {
    $l = eval("return $lines[$i];");
    $r = eval("return {$lines[$i+1]};");
    $correct = compare($l, $r);
    if ($correct) {
        $sum_group += $group;
    }
    $group++;
}

echo "$sum_group\n";

function compare($l, $r) {
    if (!is_array($l)) {
        $l = array_fill(0, count($r), $l);
    }
    if (!is_array($r)) {
        $r = array_fill(0, count($l), $r);
    }

    if (count($l) > count($r)) {
        return false;
    }

    foreach ($l as $i => $item) {
        if  (is_array($item)) {
            return compare($item, $r[$i]);
        } elseif (is_array($r[$i])) {
            return compare($item, $r[$i]);
        } elseif ($item > $r[$i]) {
            echo "$item vs $r[$i]\n";
            return false;
        }
    }

    return true;
}

// 1789 WRONG
