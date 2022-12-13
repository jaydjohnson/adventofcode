<?php

$lines = explode( PHP_EOL, file_get_contents( 'day12.in' ));

$s = [];
$e = [];
foreach ($lines as $y => $line) {
    if (strpos($line, 'S') !== false) {
        $s = [strpos($line, 'S'), $y];
    }
    if (strpos($line, 'E') !== false) {
        $e = [strpos($line, 'E'), $y];
    }
    echo $line . PHP_EOL;
}

var_dump($s);
var_dump($e);