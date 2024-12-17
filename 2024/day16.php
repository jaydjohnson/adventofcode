<?php

require 'common.php';

$lines = get_input(16, true);

$map = [];
$start = [];
$end = [];
foreach ($lines as $y => $line) {
    $map[] = str_split($line);

    if (strpos($line, 'S')) {
        $start = [X => strpos($line, 'S'), Y => $y];
    }
    if (strpos($line, 'E')) {
        $end = [X => strpos($line, 'E'), Y => $y];
    }

}


