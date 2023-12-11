<?php

require 'common.php';

$lines = get_input(11, true);

$small_galaxy = [];
foreach ($lines as $line) {
    $small_galaxy[] = str_split($line);
}

$galaxy = [];
// expand galaxy
foreach ($small_galaxy as $row) {
    if (count(array_unique($row)) === 1) {
        $galaxy[] = $row;
    }
    $galaxy[] = $row;
}

$colums = [];
for ($i = 0; $i < count($galaxy[0]); $i++) {
    if (count(array_unique(array_column($galaxy, $i))) === 1) {
        // found all .
        $columns[] = $i;
    }
}

for ($i = 0; $i < count($galaxy); $i++) {
    foreach ($columns as $y => $column) {
        array_splice($galaxy[$i], $column + $y, 0, ['.']);
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

// find distances
d($galaxies);

$sum = 0;
for ($i = 0; $i < count($galaxies); $i++) {
    for ($j = $i + 1; $j < count($galaxies); $j++) {
        $x1 = $galaxies[$i][1];
        $y1 = $galaxies[$i][0];
        $x2 = $galaxies[$j][1];
        $y2 = $galaxies[$j][0];
        $distance = abs($x2-$x1) + abs($y2-$y1);
        $sum += $distance;
        echo 'distance between ' . $i + 1 . ' and ' . $j + 1 . ' : ' . $distance . PHP_EOL;
    }
}

d($sum);
