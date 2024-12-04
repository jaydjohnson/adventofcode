<?php

$lines = explode( PHP_EOL, file_get_contents( 'day14.in' ));
$ground = [];

foreach ($lines as $line) {
    $coords = explode(' -> ', $line);
    for($i = 0; $i < count($coords)-1; $i++) {
        list($x1, $y1) = explode(',', $coords[$i]);
        list($x2, $y2) = explode(',', $coords[$i+1]);
        echo "$x1, $y1 -> $x2, $y2\n";
        if ($x1 !== $x2) {
            for($x = $x1; $x < $x2; $x++) {
                $ground[$y1][$x] = "#";
            }
        } else {
            for($y = $y1; $y < $y2; $y++) {
                $ground[$y][$x1] = "#";
            }
        }
    }
}

var_dump($ground);
foreach($ground as $g) {
    echo implode('')
}