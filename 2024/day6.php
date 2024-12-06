<?php

include 'common.php';

$lines = get_input(6, false);

$map = [];
$pos = [0,0];
foreach ($lines as $y => $line) {
    $map[] = $line;
    if (str_contains($line, '^')) {
        $pos = [$y, strpos($line, '^')];
    }
}

$steps = 0;
$dir ='n';
d($pos);
$newpos = [$pos[0] + $adjacents[$dir][0], $pos[1] + $adjacents[$dir][1]];

while (inbounds($pos[0], $pos[1], strlen($map[0]), count($map))) {
    // mark pos
    $map[$pos[0]][$pos[1]] = 'X';
    $newpos = [$pos[0] + $adjacents[$dir][0], $pos[1] + $adjacents[$dir][1]];
    if (! inbounds($newpos[0], $newpos[1], strlen($map[0]), count($map))) {
        break;
    } elseif ( $map[$newpos[0]][$newpos[1]] !== '#') {
        $steps++;
    } else {
        // turn
        $dir = match($dir) {
            'n' => 'e',
            'e' => 's',
            's' => 'w',
            'w' => 'n',
        };
        echo "switching directions $steps, $dir at $pos[0], $pos[1]\n";
    }
    $pos = [$pos[0] + $adjacents[$dir][0], $pos[1] + $adjacents[$dir][1]];
    v($pos);
}
$positions = 0;
foreach ($map as $line) {
    $positions += substr_count($line, 'X');
}
d($map);
v($positions);