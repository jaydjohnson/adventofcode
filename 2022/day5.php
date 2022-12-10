<?php

$lines = explode( PHP_EOL, file_get_contents( 'day5.in' ));

$stacks = [];
$stacks2 = [];

foreach ($lines as $k=>$line) {
    // echo $line . PHP_EOL;
    // parse crates
    $positions = [];
    $lastPos = 0;
    while (($lastPos = strpos($line, '[', $lastPos)) !== false) {
        $positions[] = $lastPos;
        $lastPos = $lastPos + strlen('[');
    }
    if (count($positions) > 0) {
        foreach ($positions as $position) {
            $stacks[$position / 4][] = substr($line, $position+1, 1);
        }
    } elseif (empty($line)) {
        // re-order stacks
        ksort($stacks);
        foreach ($stacks as $i => $stack) {
            krsort($stacks[$i]);
            $stacks[$i] = array_values($stacks[$i]);

        }
        $stacks2 = $stacks;

    } else {

        // Instructions
        if (preg_match('/move (\d+) from (\d+) to (\d+)/', $line, $instructions)) {
            if (count($instructions)) {
                for($i = 0; $i < $instructions[1]; $i++) {
                    array_push( $stacks[$instructions[3]-1], array_pop($stacks[$instructions[2]-1]));
                }

                $moving = array_splice($stacks2[$instructions[2]-1], -$instructions[1]);
                foreach ($moving as $move) {
                    array_push($stacks2[$instructions[3]-1], $move);
                }
            }
        }
    }

}

foreach ($stacks as $stack) {
    echo array_pop($stack);
}
echo PHP_EOL;

foreach ($stacks2 as $stack) {
    echo array_pop($stack);
}