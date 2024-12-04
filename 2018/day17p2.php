<?php

$file = fopen('day17.txt', 'r');
$ground = [];
$clayLines = [];
$y = 0;
while (($line = fgets($file)) !== false) {
    preg_match("/(x|y)=(\d+), [x|y]=(\d+)\.\.(\d+)/", trim($line), $matches);
    if ($matches[1] == "x") {
        $clayLines[] = ['point1' => ['x'=>$matches[2], 'y'=>$matches[3]], 'point2' => ['x'=>$matches[2], 'y'=>$matches[4]]];
        for ($y = $matches[3]; $y <= $matches[4]; $y++) {
            $ground[$y][$matches[2]] = "#";
        }
    } else {
        $clayLines[] = ['point1' => ['x'=>$matches[3], 'y'=>$matches[2]], 'point2' => ['x'=>$matches[4], 'y'=>$matches[2]]];
        for ($x = $matches[3]; $x <= $matches[4]; $x++) {
            $ground[$matches[2]][$x] = "#";
        }
    }
}

ksort($ground);
//$ground = array_slice($ground, 0, 50);

// $ground = array_slice($ground, 0, 100);
// $clayLines = array_slice($clayLines, 0, 100);

// Guesses
// 25375 - Low
// 25376 - ANSWER!!
// 25503 - ??
// 25504 - ??
// 25605 - high
// 25682 - high

global $bounds;
global $clayLines;
global $draw;
$draw = false;
$bounds = [];

foreach ($ground as $y => $line) {
    if (!isset($bounds['ymin']) || $y < $bounds['ymin']) {
        $bounds['ymin'] = $y;
    }
    if (!isset($bounds['ymax']) || $y > $bounds['ymax']) {
        $bounds['ymax'] = $y;
    }
    foreach ($line as $x => $point) {
        if (!isset($bounds['xmin']) || $x < $bounds['xmin']) {
            $bounds['xmin'] = $x;
        }
        if (!isset($bounds['xmax']) || $x > $bounds['xmax']) {
            $bounds['xmax'] = $x;
        }
    }
}

print_r($bounds);
$width = $bounds['xmax'] - $bounds['xmin'] + 10;
$ground = $ground + array_fill(0, $bounds['ymax'], []);
ksort($ground);

foreach ($ground as $y => $line) {
    $soil = array_fill($bounds['xmin']-5, $width, '.');
    $ground[$y] = $line + $soil;
    ksort($ground[$y]);
}

$ground[0][500] = "+";
saveImg($bounds, $ground, 0);
fill($ground, $clayLines, $bounds);
saveImg($bounds, $ground, 1);
//draw($ground);
countWater($ground, $bounds);
countSettledWater($ground, $bounds);

function draw($ground)
{
    global $draw;
    if (!$draw) {
        return;
    }
    foreach ($ground as $line) {
        foreach ($line as $point) {
            echo $point;
        }
        echo PHP_EOL;
    }
}

function countWater($ground, $bounds)
{
    $sum = 0;
    foreach ($ground as $y => $line) {
        foreach ($line as $x => $point) {
            if ($y >= $bounds['ymin'] && $y <= $bounds['ymax']) {
                if (in_array($point, ['~', '|', '+'])) {
                    $sum++;
                }
            }
        }
    }
    echo "total water: " . ($sum) . PHP_EOL;
}

function countSettledWater($ground, $bounds)
{
    $sum = 0;
    foreach ($ground as $y => $line) {
        foreach ($line as $x => $point) {
            if ($y >= $bounds['ymin']-1 && $y <= $bounds['ymax']) {
                if (in_array($point, ['~'])) {
                    $sum++;
                }
            }
        }
    }
    echo "total settled water: " . ($sum) . PHP_EOL;
}

function fill(&$ground, $clayLines, $bounds)
{
    $num = 2;
    $notDone = true;
    $springs = [[500, 0]];
    do {
        $waters = [];
        foreach ($springs as $k => &$spring) {
            draw($ground);
            flowDown($ground, $spring, $waters);
            draw($ground);
            //saveImg($bounds, $ground, $num);
            $num++;
            unset($springs[$k]);
        }
        foreach ($waters as $water) {
            //while (fillLeft($ground, $water, $springs) & fillRight($ground, $water, $springs)) {
            while (true) {
                $overflow = fillLine($ground, $water, $springs);
                // $l = fillLeft($ground, $water, $springs);
                // $r = fillRight($ground, $water, $springs);
                if (! $overflow) {
                    fillUp($ground, $water);
                } else {
                    //saveImg($bounds, $ground);
                    $num++;
                    break;
                }
                draw($ground);
            }
        }
    } while (sizeof($springs) > 0);
}

function fillUp(&$ground, &$water)
{
    $water = [$water[0], $water[1]-1];
    $ground[$water[1]][$water[0]] = '~';
}

function flowDown(&$ground, &$spring, &$waters)
{
    $x = $spring[0];
    $y = $spring[1];

    $nextMeter = $ground[$y+1][$x];
    while ($nextMeter == '.') {
        $ground[$y+1][$x] = '|';
        $y++;
        if ($y >= sizeof($ground)-1) {
            // left the board
            return;
        }
        $nextMeter = $ground[$y+1][$x];
    }
    $fill = false;
    if ($nextMeter == "~") {
        // check if it needs filling
        // check water to the left for either wall or spring
        // check water to right for either wall or spring
        if (checkLeft($ground, $x, $y+1) && checkRight($ground, $x, $y+1)) {
            $fill = true;
        }
    }
    if ($nextMeter == "#" || $fill) {
        $ground[$y][$x] = '~';
        $waters[] = [$x, $y];
    }
}

function checkLeft($ground, $x, $y)
{
    global $bounds;
    global $clayLines;
    reset($ground[$y]);
    $xmin = key($ground[$y]);

    //saveImg($bounds, $ground, $clayLines, 1);
    while (! in_array($ground[$y][$x], ['#', '+'])) {
        $x--;
        if ($x <= $xmin+2) {
            // left the board
            return false;
        }
    }
    if ($ground[$y][$x] == '#') {
        return true;
    }
    return false;
}

function checkRight($ground, $x, $y)
{
    end($ground[$y]);
    $xmax = key($ground[$y]);
    while (! in_array($ground[$y][$x], ['#', '+'])) {
        $x++;
        if ($x >= $xmax-2) {
            // left the board
            return false;
        }
    }
    if ($ground[$y][$x] == '#') {
        return true;
    }
    return false;
}

function fillLeft(&$ground, &$water, &$springs)
{
    $x = $water[0];
    $y = $water[1];
    reset($ground[$y]);
    $xmin = key($ground[$y]);

    $leftMeter = $ground[$y][$x-1];
    $bottomMeter = $ground[$y+1][$x-1];
    while (! in_array($leftMeter, ['#', '|', '+'])) {
        $ground[$y][$x-1] = '~';
        $x--;
        if ($bottomMeter == '.') {
            $ground[$y][$x] = "+";
            $springs[] = [$x, $y];
            return false;
        }
        if ($x <= $xmin+2) {
            // left the board
            return false;
        }
        $leftMeter = $ground[$y][$x-1];
        $bottomMeter = $ground[$y+1][$x-1];
    }
    return true;
}

function fillRight(&$ground, &$water, &$springs)
{
    $x = $water[0];
    $y = $water[1];

    $rightMeter = $ground[$y][$x+1];
    $bottomMeter = $ground[$y+1][$x+1];
    while (! in_array($rightMeter, ['#', '|', '+'])) {
        $ground[$y][$x+1] = '~';
        $x++;
        if ($bottomMeter == '.') {
            $ground[$y][$x] = "+";
            $springs[] = [$x, $y];
            return false;
        }
        $rightMeter = $ground[$y][$x+1];
        $bottomMeter = $ground[$y+1][$x+1];
    }
    return true;
}

function fillLine(&$ground, &$water, &$springs)
{
    $minx = $maxx = $water[0];
    $y = $water[1];
    $overflow = false;

    while (!in_array($ground[$y][$minx-1], ['#', '+']) && $ground[$y+1][$minx] != '.') {
        $minx--;
    }
    while (!in_array($ground[$y][$maxx+1], ['#', '+'])  && $ground[$y+1][$maxx] != '.') {
        $maxx++;
    }
    for ($x = $minx; $x <= $maxx; $x++) {
        $ground[$y][$x] = "~";
    }

    if ($ground[$y][$minx-1] == '+' || $ground[$y][$maxx+1] == '+') {
        $overflow = true;
    }
    if ($ground[$y+1][$minx] == '.') {
        $ground[$y][$minx] = "+";
        $springs[] = [$minx, $y];
        $overflow = true;
        for ($x = $minx+1; $x <= $maxx; $x++) {
            $ground[$y][$x] = '|';
        }
    }
    if ($ground[$y+1][$maxx] == '.') {
        $ground[$y][$maxx] = "+";
        $springs[] = [$maxx, $y];
        $overflow = true;
        for ($x = $minx; $x <= $maxx-1; $x++) {
            $ground[$y][$x] = '|';
        }

    }
    return $overflow;
}

function saveImg($bounds, $ground, $imageNum = 1)
{
    $xoffset = $bounds['xmin'] - 10;
    $imgWidth   = $bounds['xmax'] - $bounds['xmin'] + 20;
    $imgHeight  = $bounds['ymax'] + 10;
    $image      = imagecreate($imgWidth, $imgHeight);
    $colorWhite = imagecolorallocate($image, 255, 255, 255);
    $colorGrey  = imagecolorallocate($image, 192, 192, 192);
    $colorBlue  = imagecolorallocate($image, 0, 0, 255);
    $colorBrown = imagecolorallocate($image, 170, 75, 0);
    
    //imageline($image, 500-$xoffset, 0, 500-$xoffset, $imgHeight, $colorGrey);
    
    // Create line graph
    // foreach ($clayLines as $line) {
    //     imageline($image, $line['point1']['x'] - $xoffset, $line['point1']['y'], $line['point2']['x'] - $xoffset, $line['point2']['y'], $colorBrown);
    // }
    
    // draw water
    foreach ($ground as $y => $line) {
        foreach ($line as $x => $point) {
            if ($point == '~') {
                imagesetpixel($image, $x-$xoffset, $y, $colorBlue);
            }
            if (in_array($point, ['|', '+'])) {
                imagesetpixel($image, $x-$xoffset, $y, $colorGrey);
            }
            if ($point == "#") {
                imagesetpixel($image, $x-$xoffset, $y, $colorBrown);
            }
        }
    }
    
    // Output graph and clear image from memory
    imagepng($image, "day17-$imageNum.png");
    imagedestroy($image);
}
