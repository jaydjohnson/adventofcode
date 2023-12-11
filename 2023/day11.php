<?php

require 'common.php';

$lines = get_input(11, false);

$galaxy = [];
foreach ($lines as $line) {
    $galaxy[] = str_split($line);
}

// expand galaxy
$expanded_rows = [];
foreach ($galaxy as $r => $row) {
    if (count(array_unique($row)) === 1) {
        $expanded_rows[] = $r;
    }
}

$expanded_columns = [];
for ($i = 0; $i < count($galaxy[0]); $i++) {
    if (count(array_unique(array_column($galaxy, $i))) === 1) {
        // found all .
        $expanded_columns[] = $i;
    }
}

foreach ($galaxy as $row) {
    echo implode('', $row) . PHP_EOL;
}

$galaxies = [];
foreach ($galaxy as $y => $row) {
    foreach ($row as $x => $col) {
        if ($col === '#') {
            $galaxies[] = [$y, $x];
        }
    }
}

d($expanded_columns);
$expand_factor = 999999;
// find distances
$sum = 0;
for ($i = 0; $i < count($galaxies); $i++) {
    for ($j = $i + 1; $j < count($galaxies); $j++) {
        $x1 = $galaxies[$i][1];
        $y1 = $galaxies[$i][0];
        $x2 = $galaxies[$j][1];
        $y2 = $galaxies[$j][0];
        $distance = abs($x2 - $x1) + abs($y2 - $y1);
        foreach ($expanded_rows as $row) {
            if (! (($row < $y1 && $row < $y2) || ($row > $y1 && $row > $y2))) {
                $distance += $expand_factor;
            }
        }
        foreach ($expanded_columns as $col) {
            if (! (($col < $x1 && $col < $x2) || ($col > $x1 && $col > $x2))) {
                $distance += $expand_factor;
            }
        }
        $sum += $distance;
        echo 'distance between ' . $i + 1 . ' and ' . $j + 1 . ' : ' . $distance . PHP_EOL;
    }
}

d($sum);
