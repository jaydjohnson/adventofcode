<?php

include 'common.php';
ini_set('memory_limit', '1024M');
$lines = get_input(5, false);

for($i = 0; $i < count($lines); $i++) {
    $line = $lines[$i];

    if (str_starts_with( $line, 'seeds')) {
        $seeds = explode(' ', explode(': ', $line)[1]);
    }
    if (str_starts_with($line, 'seed-to-soil')) {
        $i++;
        while ($lines[$i] != '') {
            $sts_map[] = explode(' ', $lines[$i]);
            $i++;
        }
    }
    if (str_starts_with($line, 'soil-to-fertilizer')) {
        $i++;
        while ($lines[$i] != '') {
            $stf_map[] = explode(' ', $lines[$i]);
            $i++;
        }
    }
    if (str_starts_with($line, 'fertilizer-to-water')) {
        $i++;
        while ($lines[$i] != '') {
            $ftw_map[] = explode(' ', $lines[$i]);
            $i++;
        }
    }
    if (str_starts_with($line, 'water-to-light')) {
        $i++;
        while ($lines[$i] != '') {
            $wtl_map[] = explode(' ', $lines[$i]);
            $i++;
        }
    }
    if (str_starts_with($line, 'light-to-temperature')) {
        $i++;
        while ($lines[$i] != '') {
            $ltt_map[] = explode(' ', $lines[$i]);
            $i++;
        }
    }
    if (str_starts_with($line, 'temperature-to-humidity')) {
        $i++;
        while ($lines[$i] != '') {
            $tth_map[] = explode(' ', $lines[$i]);
            $i++;
        }
    }
    if (str_starts_with($line, 'humidity-to-location')) {
        $i++;
        while ($i < sizeof($lines) && $lines[$i] != '') {
            $htl_map[] = explode(' ', $lines[$i]);
            $i++;
        }
    }
}

function part_one() {
    foreach ($seeds as $seed) {
        $soil = lup($seed, $sts_map);
        $fert = lup($soil, $stf_map);
        $water = lup($fert, $ftw_map);
        $light = lup($water, $wtl_map);
        $temp = lup($light, $ltt_map);
        $hum = lup($temp, $tth_map);
        $loc = lup($hum, $htl_map);
        echo $loc . PHP_EOL;
    }  
}


$new_seeds = [];
$lowest = 0;
for($s = 0; $s < count($seeds); $s = $s+2) {
    $start = $seeds[$s];
    $count = $seeds[$s+1];
    echo "Check seeds " . $start . " to " . $start+$count . PHP_EOL;
    for($x = 0; $x<$count; $x++) {
        $soil = lup($start+$x, $sts_map);
        $fert = lup($soil, $stf_map);
        $water = lup($fert, $ftw_map);
        $light = lup($water, $wtl_map);
        $temp = lup($light, $ltt_map);
        $hum = lup($temp, $tth_map);
        $loc = lup($hum, $htl_map);
        if ($loc < $lowest || $lowest === 0) {
            $lowest = $loc;
        }
    }
}
echo $lowest . PHP_EOL;


function lup($num, $source) {
    foreach ($source as $range) {
        $source = (int)$range[1];
        $dest = (int)$range[0];
        $count = (int)$range[2];
        if ($num >= $source && $num <= $source+$count) {
            return $dest + ($num-$source);
        }
    }
    return $num;
}