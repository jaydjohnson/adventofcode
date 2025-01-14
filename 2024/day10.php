<?php

require 'common.php';

$lines = get_input(10, false);

$map = [];
$heads = [];
foreach ($lines as $y => $line) {
    $map[] = $line;
    $heights = str_split($line);
    array_walk($heights, function ($v, $k) use ($y, &$heads) {
        if ($v === '0') {
            $heads[] = [Y => $y, X => $k];
        }
    });
}

d($heads);
$c = 0;
$orimap = $map;
foreach ($heads as $head) {
    [$c, $map] = findNine($head, 0, $orimap, $c);
}
// [$c, $map] = findNine([Y => 0, X => 3], 0, $map, 0);
d($c);



function findNine($pos, $height, $map, $count) {
    if ($height == '9') {
        echo "FOUND 9!!\n";
        $count++;
        // Uncomment for Part 1
        //$map[$pos[Y]][$pos[X]] = 'X';
        return [$count, $map];
    }

    // N
    if (inbounds($pos[Y] - 1, $pos[X], strlen($map[0]), count($map))) {
        //echo "checking north " . $map[$pos[Y] - 1][$pos[X]] . ' ' . $height + 1 . PHP_EOL;
        if ($map[$pos[Y] - 1][$pos[X]] == $height + 1) {
            echo "moving N to " . $pos[Y] - 1 . ' ' . $pos[X] . PHP_EOL;
            [$count, $map] = findNine([Y => $pos[Y] - 1, X => $pos[X]], $height + 1, $map, $count);
        }
    }

    // S
    if (inbounds($pos[Y] + 1, $pos[X], strlen($map[0]), count($map))) {
        //echo "checking south ({$pos[Y]}, {$pos[X]})" . $map[$pos[Y] + 1][$pos[X]] . ' ' . $height + 1 . PHP_EOL;
        if ($map[$pos[Y] + 1][$pos[X]] == $height + 1) {
            echo "moving S to " . $pos[Y] + 1 . ' ' . $pos[X] . PHP_EOL;
            [$count, $map] = findNine([Y => $pos[Y] + 1, X => $pos[X]], $height + 1, $map, $count);
        }
    }

    // E
    if (inbounds($pos[Y], $pos[X] + 1, strlen($map[0]), count($map))) {
        //l("({$pos[Y]}, {$pos[X]}) " . strlen($map[0]) . count($map));
        //echo "checking east " . $map[$pos[Y]][$pos[X] + 1] . ' ' . $height + 1 . PHP_EOL;
        if ($map[$pos[Y]][$pos[X] + 1] == $height + 1) {
            echo "moving E to " . $pos[Y] . ' ' . $pos[X] + 1 . PHP_EOL;
            [$count, $map] = findNine([Y => $pos[Y], X => $pos[X] + 1], $height + 1, $map, $count);
        }
    }

    // W
    if (inbounds($pos[Y], $pos[X] - 1, strlen($map[0]), count($map))) {
        //echo "checking west " . $map[$pos[Y]][$pos[X] - 1] . ' ' . $height + 1 . PHP_EOL;
        if ($map[$pos[Y]][$pos[X] - 1] == $height + 1) {
            echo "moving W to " . $pos[Y] . ' ' . $pos[X] - 1 . PHP_EOL;
            [$count, $map] = findNine([Y => $pos[Y], X => $pos[X] - 1], $height + 1, $map, $count);
        }
    }

    return [$count, $map];
}
