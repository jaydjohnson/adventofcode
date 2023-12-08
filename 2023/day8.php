<?php

include 'common.php';

$lines = get_input(8, false);
$nodes = [];
foreach ($lines as $k => $line) {
    if ($k===0) {
        $instructions = $line;
        continue;
    }
    if (empty($line)) {
        continue;
    }
    [$index, $next_elements] = explode(' = ', $line);
    $next_elements = explode(', ', str_replace(['(',')'], '', $next_elements));
    $nodes[$index] = $next_elements;
}

// $current_instruction = 0;
// $current_node = 'AAA';
// $steps = 0;
// do {
//     if ($current_instruction >= strlen($instructions)) {
//         $instructions .= $instructions;
//     }
//     d($instructions);
//     $dir = $instructions[$current_instruction];
//     $current_node = $nodes[$current_node][$dir === 'L' ? 0 : 1];
//     $steps++;
//     $current_instruction++;
//     echo $steps . PHP_EOL;

// } while ($current_node !== 'ZZZ');

// echo "part 1: $steps" . PHP_EOL;

$anodes = array_filter( $nodes, function($item, $k) {
    return str_ends_with($k, 'A');
}, ARRAY_FILTER_USE_BOTH );

$current_instruction = 0;
$steps = 0;
do {
    if ($current_instruction >= strlen($instructions)) {
        $instructions .= $instructions;
    }
    $dir = $instructions[$current_instruction];

    $new_anodes = [];
    foreach($anodes as $k => $anode) {
        $new_index = $nodes[$k][$dir === 'L' ? 0 : 1];
        $new_nodes = $nodes[$new_index];
        $new_anodes[$new_index] = $new_nodes;
    }
    $anodes = $new_anodes;
    $steps++;
    $current_instruction++;
    echo $steps . PHP_EOL;
    $continue = false;
    foreach($anodes as $k => $anode) {
        if (!str_ends_with($k, 'Z')) {
            $continue = true;
        }
    }
    if (!$continue) {
        break;
    }
} while (true);
