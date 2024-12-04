<?php
include(__DIR__.'/utils.php');

gc_disable();
ini_set('memory_limit', '1024M');

$file = fopen('day10.txt', 'r');
$stars = [];

$xmin = 0;
$ymin = 0;

while (($line = fgets($file)) !== false) {
	preg_match('/position=<(.?.?\d+), (.?.?\d+)> velocity=<(.?.?\d+), (.?.?\d+)>/', $line, $matches);
	$stars[] = new Star($matches[1], $matches[2], $matches[3], $matches[4]);
	if ($matches[1] < $xmin) {
		$xmin = $matches[1];
	}
	if ($matches[2] < $ymin) {
		$ymin = $matches[1];
	}
}

//print_r($stars);

// foreach ($stars as $star) {
// 	$star->offset(-$xmin, -$ymin);
// }

foreach (range(0, 11000) as $step) {
	print_stars($stars, $step);
	update_stars($stars);
}

fclose($file);

function print_stars($stars, $seconds) {
	$grid = [];
	$xmin = $stars[0]->x;
	$xmax = $stars[0]->x;
	$ymin = $stars[0]->y;
	$ymax = $stars[0]->y;;

	foreach ($stars as $star) {
		$xmin = $star->x <= $xmin ? $star->x - 2: $xmin;
		$xmax = $star->x >= $xmax ? $star->x + 2: $xmax;
		$ymin = $star->y <= $ymin ? $star->y - 2: $ymin;
		$ymax = $star->y >= $ymax ? $star->y + 2: $ymax;
		//echo "$star->x, $star->y, $ymin, $ymax" . PHP_EOL;
	}
	$height = $ymax-$ymin;
	$width = $xmax-$xmin;
	$grid = array_fill(0, $height, array_fill(0, $width+1, '.'));

	if ($height > 80) return;
	echo PHP_EOL . PHP_EOL . "Seconds: $seconds, width: $width, height: $height, xmin: $xmin, xmax: $xmax, ymin: $ymin, ymax: $ymax" . PHP_EOL;


	foreach ($stars as $star) {
		$grid[$star->y - $ymin][$star->x - $xmin] = '#';
	}
	foreach ($grid as $gx) {
		echo ':' . join('', $gx) . PHP_EOL;
	}
}

function update_stars(&$stars) {
	foreach ($stars as $star) {
		$star->move();
	}
}

function line2digits(string $line, $withsigns=true): array 
{ 
	$res = []; 
	$regexp = ($withsigns) ? "/([+-]?\d+)/" : "/(\d+)/"; 
	if (preg_match_all($regexp,$line,$b)) {
		$res = Acast2ints(Afirst($b)); 
	}
	return $res; 
}

class Star
{
    public $x;
    public $y;
    public $vx;
    public $vy;

    public function __construct($x, $y, $vx, $vy)
    {
    	$this->x = $x;
    	$this->y = $y;
    	$this->vx = $vx;
    	$this->vy = $vy;
    }

    public function move()
    {
    	$this->x += $this->vx;
    	$this->y += $this->vy;
    }

    public function offset($x, $y)
    {
    	$this->x += $x;
    	$this->y += $y;
    }
}