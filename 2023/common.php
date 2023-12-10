<?php

$adjacents     = [
	[
		1,
		1,
	],
	[
		1,
		0,
	],
	[
		0,
		-1,
	],
	[
		-1,
		-1,
	],
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


function get_input($day, $example = false)
{
	return explode(PHP_EOL, file_get_contents('day'.$day.($example ? '.ex' : '.in')));

}//end get_input()


function inbounds($row, $column, $width, $height)
{
	return $row >= 0 && $row < $width && $column >= 0 && $column < $height;

}//end inbounds()


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

}//end getFactors()


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

}//end getGreatestCommonDenominator()


function d($var)
{
	var_dump($var);

}//end d()


function dd($var)
{
	var_dump($var);
	die();

}//end dd()
