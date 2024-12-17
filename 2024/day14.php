<?php

require 'common.php';

$lines = get_input(14, false);

$width = 100;
$height = 102;
$bots = [];
foreach ($lines as $y => $line) {
    preg_match('/p=(\d+),(\d+) v=(\-?\d+),(\-?\d+)/', $line, $matches);
    $bots[] = [
        'p' => [X=>intval($matches[1]), Y=>intval($matches[2])], 
        'v' => [X=>intval($matches[3]), Y=>intval($matches[4])]
    ];
}
$quads = [1=>0,2=>0,3=>0,4=>0];
foreach ($bots as $bot) {
    // v($bot['v']);
    // v($bot['p']);
    foreach (range(1, 100) as $move) {
        $dx = $bot['p'][X] + $bot['v'][X];
        $dy = $bot['p'][Y] + $bot['v'][Y];
        if ($dx > $width) {
            $bot['p'][X] = $dx - $width - 1;
        } elseif ($dx < 0) {
            $bot['p'][X] = $dx + $width + 1;
        } else {
            $bot['p'][X] = $dx;
        }
        if ($dy > $height) {
            $bot['p'][Y] = $dy - $height - 1;    
        } elseif ($dy < 0) {
            $bot['p'][Y] = $dy + $height + 1;
        } else {
            $bot['p'][Y] = $dy;
        }

    }
    if ($bot['p'][X] < $width / 2 && $bot['p'][Y] < $height / 2) {
        $quads[1]++;
    }
    if ($bot['p'][X] > $width / 2 && $bot['p'][Y] < $height / 2) {
        $quads[2]++;
    }
    if ($bot['p'][X] < $width / 2 && $bot['p'][Y] > $height / 2) {
        $quads[3]++;
    }
    if ($bot['p'][X] > $width / 2 && $bot['p'][Y] > $height / 2) {
        $quads[4]++;
    }
}
v($quads);
v($quads[1] * $quads[2] * $quads[3] * $quads[4]);

// part 2
foreach (range(1, 10000) as $second) {
    $quads = [1=>0,2=>0,3=>0,4=>0];
    foreach ($bots as $i => $bot) {
        $dx = $bot['p'][X] + $bot['v'][X];
        $dy = $bot['p'][Y] + $bot['v'][Y];
        if ($dx > $width) {
            $bot['p'][X] = $dx - $width - 1;
        } elseif ($dx < 0) {
            $bot['p'][X] = $dx + $width + 1;
        } else {
            $bot['p'][X] = $dx;
        }
        if ($dy > $height) {
            $bot['p'][Y] = $dy - $height - 1;    
        } elseif ($dy < 0) {
            $bot['p'][Y] = $dy + $height + 1;
        } else {
            $bot['p'][Y] = $dy;
        }

        if ($bot['p'][X] < $width / 2 && $bot['p'][Y] < $height / 2) {
            $quads[1]++;
        }
        if ($bot['p'][X] > $width / 2 && $bot['p'][Y] < $height / 2) {
            $quads[2]++;
        }
        if ($bot['p'][X] < $width / 2 && $bot['p'][Y] > $height / 2) {
            $quads[3]++;
        }
        if ($bot['p'][X] > $width / 2 && $bot['p'][Y] > $height / 2) {
            $quads[4]++;
        }

        $bots[$i]['p'] = $bot['p'];

    }
    saveImg($bots, $second);
    if ($quads[1] == $quads[2] && $quads[3] == $quads[4]) {
        // Found mirror
        l("found mirror: $second");
    }
}

function saveImg($bots, $imageNum = 1)
{
    $imgWidth   = 101;
    $imgHeight  = 103;
    $image      = imagecreate($imgWidth, $imgHeight);
    $colorWhite = imagecolorallocate($image, 255, 255, 255);
    $colorGrey  = imagecolorallocate($image, 192, 192, 192);
    $colorBlue  = imagecolorallocate($image, 0, 0, 255);
    $colorBrown = imagecolorallocate($image, 170, 75, 0);
    
    // Create line graph
    // foreach ($clayLines as $line) {
    //     imageline($image, $line['point1']['x'] - $xoffset, $line['point1']['y'], $line['point2']['x'] - $xoffset, $line['point2']['y'], $colorBrown);
    // }
    
    // draw water
    foreach ($bots as $bot) {
        imagesetpixel($image, $bot['p'][X] + 1, $bot['p'][Y] + 1, $colorBlue);
    }
    
    // Output graph and clear image from memory
    imagepng($image, "images/day14-$imageNum.png");
    imagedestroy($image);
}