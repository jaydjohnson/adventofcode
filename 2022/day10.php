<?php

global $lines;
$lines = explode(PHP_EOL, file_get_contents('day10.in'));

function getline($line_num) {
    global $lines;
    $cmds = explode(' ', $lines[$line_num]);
    $line_num++;
    if (count($cmds)>1) {
        list($cmd, $num) = $cmds;
    } else {
        $cmd = $cmds[0];
        $num = null;
    }
    return [$cmd, $num];
}

$cycle = 0;
$x = 1;
$line_num = 0;
$waiting = true;
$eof = false;
$signal_sum = 0;

while (true) {
    $cycle++;
    if ($waiting) {
        if ($line_num < count($lines)) {
            list($cmd, $num) = getline($line_num);
            $line_num++;
        } else {
            $eof = true;
        }
        $waiting = $cmd == 'noop';
    } else {
        if ($cmd == 'addx') {
            $x += $num;
        }
        $waiting = true;
        if ($eof) {
            break;
        }
    }

    echo "$cycle, $cmd, $num, $x\n";

    if (in_array($cycle, [20, 60, 100, 140, 180, 220])) {
        echo $signal_sum . ' ' . ($cycle * $x) . PHP_EOL;
        $signal_sum += ($cycle * $x);
        echo $signal_sum . PHP_EOL;
    }
    if ($cycle > 230) {
        echo $signal_sum . PHP_EOL;
        break;
    }
}
