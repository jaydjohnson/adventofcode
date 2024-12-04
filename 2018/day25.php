<?php

$points = [];
$file = fopen("day25.txt", "r");

while (($line = fgets($file)) !== false) {
    preg_match("/(-?\d+),(-?\d+),(-?\d+),(-?\d+)/", $line, $matches);
    array_shift($matches);
    $points[] = $matches;
}

//$constellations[]  = [$points[0]];
$constellations = [];

foreach ($points as $point) {
    $constFound = [];

    foreach ($constellations as $k => $const) {
        foreach ($const as $constPoints) {
            if ($point != $constPoints) {
                $distance = getDistance($point, $constPoints);
                if ($distance <= 3) {
                    // Count how many times it matches a constellation
                    // Keep track of each match and then combine the 2 constellations
                    $constFound[$k] = $k;
                }
            }
        }
    }
    // Is there a new Constellation??
    if (empty($constFound)) {
        $constellations[] = [$point];
    } else {
        if (sizeof($constFound) == 1) {
            $constKey = array_pop($constFound);
            $constellations[$constKey][] = $point;
        } else {
            // add to 1st constellation
            $Key = array_shift($constFound);
            $constellations[$Key][] = $point;
            $newConst = $constellations[$Key];
            foreach ($constFound as $constKey) {
                // Combine all 3 of these
                foreach ($constellations[$constKey] as $kpoint) {
                    array_push($constellations[$Key], $kpoint);
                }
                unset($constellations[$constKey]);
            }
        }
    }
    // else might have to check to see if new point combines multiple?
}

echo count($constellations);

function getDistance($a, $b)
{
    $distance = abs($a[0] - $b[0]) + abs($a[1] - $b[1]) + abs($a[2] - $b[2]) + abs($a[3] - $b[3]);

    return $distance;
}
