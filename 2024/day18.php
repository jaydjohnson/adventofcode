<?php

require 'common.php';

$lines = get_input(18, true);

$size = 7;
$bytes = 12;
$map = array_fill(0, $size, array_fill(0, $size, '.'));
foreach ($lines as $y => $line) {
    $coords = explode(',', $line);
    $map[$coords[1]][$coords[0]] = '#';
    if ($y == $bytes - 1) {
        break;
    }
}
board($map);
