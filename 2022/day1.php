<?php

$handle = fopen("day1-input.txt", "r");

$elfs = [0=>0];
$current_elf = 0;
if ($handle) {
    while (($line = fgets($handle)) !== false) {
        // process the line read.
        if ( $line !== PHP_EOL ) {
            $elfs[$current_elf] += $line;
        } else {
            $current_elf++;
            $elfs[$current_elf] = 0;
        }
    }

    fclose($handle);
}

rsort($elfs);
var_dump($elfs);

echo $elfs[0] + $elfs[1] + $elfs[2];