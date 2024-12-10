<?php

class MinPriorityQueue extends \SplPriorityQueue
{
    public function compare(mixed $priority1, mixed $priority2): int
    {
        return $priority2 <=> $priority1;
    }
}


const EAST = 'east';
const WEST = 'west';
const NORTH = 'north';
const SOUTH = 'south';
const X = 'x';
const Y = 'y';

// y, x
$adjacents     = [
    'e' => [0,1],
    's'  => [1,0],
    'w' => [0,-1],
    'n'  => [-1,0,]
];
$adjacentdiags = [
    [
        -1,
        -1,
    ],
    [
        -1,
        0,
    ],
    [
        -1,
        1,
    ],
    [
        0,
        1,
    ],
    [
        1,
        1,
    ],
    [
        1,
        0,
    ],
    [
        1,
        -1,
    ],
    [
        0,
        -1,
    ],
];
$diags = [
    [
        -1,
        -1,
    ],
    [
        -1,
        1,
    ],
    [
        1,
        1,
    ],
    [
        1,
        -1,
    ],
];

$opposites = [
    [[-1, -1], [1, 1]],
    [[-1, 1], [1, -1]],
    [[1, 0], [-1, 0]],
    [[0, 1], [0, -1]],
];

function get_input($day, $example = false)
{
    return explode(PHP_EOL, file_get_contents('day' . $day . ($example ? '.ex' : '.in')));
}


function inbounds($row, $column, $width, $height)
{
    return $row >= 0 && $row < $width && $column >= 0 && $column < $height;
}

/**
 * @param  int $num
 * @return array The common factors of $num
 */
function getFactors($num)
{
    $factors = [];
    // get factors of the numerator
    for ($x = 1; $x <= $num; $x++) {
        if (($num % $x) == 0) {
            $factors[] = $x;
        }
    }
    return $factors;
}


/**
 * @param int $x
 * @param int $y
 */
function getGreatestCommonDenominator($x, $y)
{
    // first get the common denominators of both numerator and denominator
    $factorsX = getFactors($x);
    $factorsY = getFactors($y);

    // common denominators will be in both arrays, so get the intersect
    $commonDenominators = array_intersect($factorsX, $factorsY);

    // greatest common denominator is the highest number (last in the array)
    $gcd = array_pop($commonDenominators);
    return $gcd;
}


function d($var)
{
    var_dump($var);
}


function dd($var)
{
    var_dump($var);
    die();
}

function v($var, $simple = true)
{
    if ($simple) {
        $text = var_export($var, true);
        $text = str_replace("\n", '', $text);
        $text = str_replace(" ", '', $text);
        $text = str_replace("array(", '[', $text);
        $text = str_replace(")", ']', $text);
        echo $text . PHP_EOL;
    } else {
        var_export($var);
    }
}


function vd($var, $simple = true)
{
    if ($simple) {
        $text = var_export($var, true);
        $text = str_replace("\n", '', $text);
        $text = str_replace(" ", '', $text);
        $text = str_replace("array(", '[', $text);
        $text = str_replace(")", ']', $text);
        echo $text . PHP_EOL;
    } else {
        var_export($var);
    }
    die();
}

function board($board) {
    echo PHP_EOL;
    foreach($board as $line) {
        echo $line . PHP_EOL;
    }
    echo PHP_EOL;
}