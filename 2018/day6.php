<?php

$coords = [];
$safeAreaSize = 10000;
$lines  = file("day6.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

foreach ($lines as $k => $line) {
    preg_match('/(\d+), (\d+)/', $line, $matches);
    array_shift($matches);
    $coords[chr(65 + $k)] = $matches;
}

$bounds = getBounds($coords);
$board  = array_fill(0, $bounds['ymax'] + 1, array_fill(0, $bounds['xmax'] + 2, '.'));
list($areaSize, $grid, $safeSize) = getAreas($board, $coords, $bounds);

echo 'Part 1: ', max($areaSize), "\n";
echo 'Part 2: ', $safeSize, "\n";

function getAreas($board, $coords, $bounds, $extra = 0)
{
    global $safeAreaSize;
    $safeSize = 0;
    $areaSize = [];
    $grid     = [];
    $myMinX   = $bounds['xmin'] - $extra;
    $myMinY   = $bounds['ymin'] - $extra;
    $myMaxX   = $bounds['xmax'] + $extra;
    $myMaxY   = $bounds['ymax'] + $extra;
    foreach (yieldXY($myMinX, $myMinY, $myMaxX, $myMaxY) as $x => $y) {
        $edge                  = in_array($x, [$myMinX, $myMaxX]) || in_array($y, [$myMinY, $myMaxY]);
        list($closest, $total) = getGridData($x, $y, $coords);
        $id                    = count($closest) == 1 ? $closest[0] : '';
        // Is this safe?
        if ($total < $safeAreaSize) {
            $safeSize++;
            // If the safe area touches the boundary, we need to expand our
            // search grid. Fuck you MD87...
            if ($edge) {
                return getAreas($board, $coords, $bounds, ceil($safeAreaSize / count($coords)) + 1);
            }
        }
        // We only care about the area sizes for areas within the normal
        // boundary.
        if ($x >= $bounds['xmin'] && $x <= $bounds['xmax'] && $y >= $bounds['ymin'] && $y <= $bounds['ymax']) {
            $edge         = in_array($x, [$bounds['xmin'], $bounds['xmax']]) || in_array($y, [$bounds['ymin'], $bounds['ymax']]);
            $grid[$y][$x] = $id;
            if ($id !== '') {
                if ($edge) {
                    $areaSize[$id] = -1;
                } else {
                    if (!isset($areaSize[$id])) {
                        $areaSize[$id] = 0;
                    }
                    if ($areaSize[$id] >= 0) {
                        $areaSize[$id]++;
                    }
                }
            }
        }
    }
    return [$areaSize, $grid, $safeSize];
}

function manhattan($x1, $y1, $x2, $y2)
{
    return abs($x1 - $x2) + abs($y1 - $y2);
}

function getGridData($x, $y, $coords)
{
    $total           = 0;
    $closestDistance = PHP_INT_MAX;
    $closest         = [];
    foreach ($coords as $id => $c) {
        $distance = manhattan($x, $y, $c[0], $c[1]);
        $total += $distance;
        if ($distance < $closestDistance) {
            $closest         = [$id];
            $closestDistance = $distance;
        } else if ($distance == $closestDistance) {
            $closest[] = $id;
        }
    }
    return [$closest, $total];
}

function checkBounds($x, $y, $bounds)
{
    if ($x < $bounds['xmin'] || $x > $bounds['xmax']) {
        return false;
    }
    if ($y < $bounds['ymin'] || $y > $bounds['ymax']) {
        return false;
    }
    return true;
}

function draw($board)
{
    foreach ($board as $line) {
        echo implode("", $line) . PHP_EOL;
    }
}

function getBounds($coords)
{
    $bounds = [];

    foreach ($coords as $coord) {
        $x = $coord[0];
        $y = $coord[1];

        if (!isset($bounds['ymin']) || $y < $bounds['ymin']) {
            $bounds['ymin'] = $y;
        }
        if (!isset($bounds['ymax']) || $y > $bounds['ymax']) {
            $bounds['ymax'] = $y;
        }
        if (!isset($bounds['xmin']) || $x < $bounds['xmin']) {
            $bounds['xmin'] = $x;
        }
        if (!isset($bounds['xmax']) || $x > $bounds['xmax']) {
            $bounds['xmax'] = $x;
        }
    }

    return $bounds;
}

function yieldXY($startx, $starty, $endx, $endy, $inclusive = true)
{
    for ($x = $startx; $x <= ($inclusive ? $endx : $endx - 1); $x++) {
        for ($y = $starty; $y <= ($inclusive ? $endy : $endy - 1); $y++) {
            yield $x => $y;
        }
    }
}

function saveImg($bounds, $ground, $imageNum = 1)
{
    $xoffset    = $bounds['xmin'] - 10;
    $imgWidth   = $bounds['xmax'] - $bounds['xmin'] + 20;
    $imgHeight  = $bounds['ymax'] + 10;
    $image      = imagecreate($imgWidth, $imgHeight);
    $colorWhite = imagecolorallocate($image, 255, 255, 255);
    $colorGrey  = imagecolorallocate($image, 192, 192, 192);
    $colorBlue  = imagecolorallocate($image, 0, 0, 255);
    $colorBrown = imagecolorallocate($image, 170, 75, 0);

    imageline($image, 500 - $xoffset, 0, 500 - $xoffset, $imgHeight, $colorGrey);

    // Create line graph
    // foreach ($clayLines as $line) {
    //     imageline($image, $line['point1']['x'] - $xoffset, $line['point1']['y'], $line['point2']['x'] - $xoffset, $line['point2']['y'], $colorBrown);
    // }

    // draw water
    foreach ($ground as $y => $line) {
        foreach ($line as $x => $point) {
            if (in_array($point, ['~', '|', '+'])) {
                imagesetpixel($image, $x - $xoffset, $y, $colorBlue);
            }
            if ($point == "#") {
                imagesetpixel($image, $x - $xoffset, $y, $colorBrown);
            }
        }
    }

    // Output graph and clear image from memory
    imagepng($image, "day17-$imageNum.png");
    imagedestroy($image);
}
