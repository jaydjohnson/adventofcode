<?php

$adjacents = [[1, 1], [1, 0], [0, -1], [-1, -1]];
$adjacentdiags = [[-1, -1], [-1, 0], [-1, 1], [0, 1], [1, 1], [1, 0], [1, -1], [0, -1]];

function get_input($day, $example = false) {
    return explode(PHP_EOL, file_get_contents('day' . $day . ($example ? '.ex' : '.in')));
}

function inbounds($row, $column, $width, $height) {
    return $row > 0 && $row < $width && $column > 0 && $column < $height;
}

function d($var) {
    var_dump($var);
}

function dd($var) {
    var_dump($var);
    die();
}