<?php

$lines = explode( PHP_EOL, file_get_contents( 'day4.in' ));

$found_containing_pairs = 0;
$found_overlap_pairs = 0;

foreach ($lines as $k=>$line) {
    echo $line . PHP_EOL;
    $pairs = explode(',', $line);
    list($pair1s, $pair1e) = explode('-', $pairs[0]);
    list($pair2s, $pair2e) = explode('-', $pairs[1]);
    
    if ($pair1s >= $pair2s && $pair1e <= $pair2e ||
        $pair2s >= $pair1s && $pair2e <= $pair1e) {
        echo 'found ' .$pair1s . '-' . $pair1e . PHP_EOL;
        $found_containing_pairs++;
    }

    if ($pair1s >= $pair2s && $pair1s <= $pair2e ||
        $pair1e >= $pair2s && $pair1e <= $pair2e ||
        $pair2s >= $pair1s && $pair2s <= $pair1e ||
        $pair2e >= $pair1s && $pair2e <= $pair1e) {
        echo 'overlap ' .$pair1s . '-' . $pair1e . PHP_EOL;
        $found_overlap_pairs++;
    }
}

echo $found_containing_pairs . PHP_EOL;
echo $found_overlap_pairs . PHP_EOL;