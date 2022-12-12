<?php

global $lines;
global $signal_sum;
$lines = explode(PHP_EOL, file_get_contents('day10.in'));

function getline($line) {
    $cmds = explode(' ', $line);
    if (count($cmds)>1) {
        list($cmd, $num) = $cmds;
    } else {
        $cmd = $cmds[0];
        $num = null;
    }
    return [$cmd, $num];
}

function handle_tick($cycle, $x) {
    global $signal_sum;
    global $screen;

    $t1 = $cycle-1;
    echo "$cycle $t1 $x " . $t1%40 . " " . abs($x-($t1%40)) . "\n";
    if (abs($x-($t1%40)) <= 1) {
        $screen[$t1/40][$t1%40] = "#";
    } else {
        $screen[$t1/40][$t1%40] = " ";
    }
    if (in_array($cycle, [20, 60, 100, 140, 180, 220])) {
        $signal_sum += ($cycle * $x);
    }
}

$cycle = 0;
$x = 1;
$line_num = 0;
$waiting = true;
$eof = false;
$signal_sum = 0;
$screen = array_fill(0, 6, array_fill(0, 40, '?'));

foreach ($lines as $line) {
    list($cmd, $num) = getline($line);
    if ($cmd == 'noop') {
        $cycle++;
        handle_tick($cycle, $x);
    } elseif ($cmd == 'addx') {
        $cycle++;
        handle_tick($cycle, $x);
        $cycle++;
        handle_tick($cycle, $x);
        $x += $num;
    }
}

echo $signal_sum . PHP_EOL;
foreach ($screen as $sl) {
    echo implode('', $sl) . PHP_EOL;
}