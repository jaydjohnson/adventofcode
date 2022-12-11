<?php

$lines = explode( PHP_EOL, file_get_contents( 'day9.in' ));

function adjust($h, $t) {
    $diff_r = $h[0] - $t[0];
    $diff_c = $h[1] - $t[1];
    //echo "$diff_r $diff_c\n";
    if (abs($diff_r) <= 1 && abs($diff_c) <= 1) {
        return $t;
    } elseif (abs($diff_r) >= 2 && abs($diff_c) >= 2) {
        // catch up diag
        $t = [$t[0] < $h[0] ? $h[0]-1 : $h[0]+1, $t[1] < $h[1] ? $h[1]-1 : $h[1]+1];
    } elseif (abs($diff_r) >= 2) {
        // catch up row
        $t = [$t[0] < $h[0] ? $h[0]-1 : $h[0]+1, $h[1]];
    } elseif (abs($diff_c) >= 2) {
        $t = [$h[0], $t[1]<$h[1] ? $h[1]-1 : $h[1]+1];
    }
    return $t;
}

$h = [0, 0];
foreach (range(0, 8) as $_) {
    $t[] = [0, 0];
}

$dr = ['L' => 0, 'U' => -1, 'R' => 0, 'D' => 1];
$dc = ['L' => -1, 'U' => 0, 'R' => 1, 'D' => 0];
$tpos = [];
$tpos2 = [];

foreach ($lines as $k=>$line) {
    list($d, $amt) = explode(' ', $line);

    foreach(range(1, $amt) as $x) {

        $h = [$h[0] + $dr[$d], $h[1] + $dc[$d]];
        $t[0] = adjust($h, $t[0]);
        // echo "Moved T: $h[0] $h[1] - $t[0] $t[1]\n";
        foreach (range(1, 8) as $i) {
            $t[$i] = adjust($t[$i-1], $t[$i]);
        }
        $tpos[$t[0][0].','.$t[0][1]] = $t[0];
        $tpos2[$t[8][0].','.$t[8][1]] = $t[8];
    }
}

echo count($tpos) . PHP_EOL;
echo count($tpos2) . PHP_EOL;