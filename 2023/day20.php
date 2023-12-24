<?php

require 'common.php';

$lines = get_input(20, true);
// $parts = file_get_contents('day20.in');

$modules = [];
foreach ($lines as $line) {
    [$mod, $output] = explode(' -> ', $line);
    $modules[substr($mod, 1)] = [
        $mod === 'broadcaster' ? '*' : substr($mod, 0, 1),
        array_map('trim', explode(',', $output)),
        false,
    ];
}

$q = [['roadcaster', false]];
$pulses = 0;

while (! empty($q)) {
    [$current, $pulse] = array_shift($q);
    // echo "$current: " . ($pulse ? 'high' : 'low') . PHP_EOL;
    if (! isset($modules[$current])) {
        continue;
    }

    [$type, $outputs, $mod_pulse] = $modules[$current];

    if ($type === '%' && $pulse) {
        $modules[$current][2] = $pulse;
        continue;
    }

    if ($type === '%') {
        $pulse = ! $mod_pulse;
    }

    if ($type === '&') {
        $pulse = ! $pulse;
    }

    $modules[$current][2] = $pulse;

    foreach ($outputs as $output) {
        echo "Sending: $current " . ($pulse ? 'high' : 'low') . " -> $output" . PHP_EOL;
        // v($modules);
        $q[] = [$output, $pulse];
    }
    // v($modules);
    // vd($q);
}
