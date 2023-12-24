<?php

require 'common.php';

$lines = get_input(21, true);
// $parts = file_get_contents('day20.in');

$garden = [];
$start = [];
foreach ($lines as $y => $line) {
    if (str_contains($line, 'S')) {
        $start = [$y, strpos($line, 'S')];
        $line = str_replace('S', '.', $line);
    }
    $garden[] = str_split($line);
}

$positions[] = $start;

for ($s = 0; $s < 6; $s++) {
    $new_positions = [];
    foreach ($positions as [$r, $c]) {
        foreach ($adjacents as [$dr, $dc]) {
            if (inbounds($r + $dr, $c + $dc, count($garden[0]), count($garden))) {
                if ($garden[$r + $dr][$c + $dc] !== '#' && ! in_array([$r + $dr, $c + $dc], $new_positions)) {
                    $new_positions[] = [$r + $dr, $c + $dc];
                }
            }
        }
    }
    $positions = $new_positions;
}

d(count($new_positions));

// Parte 2
$positions = [$start];

for ($s = 0; $s < 100; $s++) {
    $new_positions = [];
    foreach ($positions as [$r, $c]) {
        foreach ($adjacents as [$dr, $dc]) {
            $y = ($r + $dr) < 0 ? count($garden) - abs(($r + $dr) % count($garden)) : ($r + $dr) % count($garden);
            $x = ($c + $dc) < 0 ? count($garden[0]) - abs(($c + $dc) % count($garden[0])) : ($c + $dc) % count($garden[0]);
            echo "$r, $c  ($dr, $dc)  -->  $y $x" . PHP_EOL;
            if ($garden[$y][$x] !== '#' && ! in_array([$r + $dr, $c + $dc], $new_positions)) {
                $new_positions[] = [$r + $dr, $c + $dc];
            }
        }
    }
    $positions = $new_positions;
}

d(count($new_positions));

// 1 = 10
// 2 = 9
// 3 = 8
// 4 = 7
// 5 = 6
// 6 = 5
// 7 = 4
// 8 = 3
// 9 = 2
// 10 = 1
// 11 = 0