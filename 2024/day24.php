<?php

require 'common.php';

$groups = get_input(24, false, true);

$wires = [];
foreach (explode(PHP_EOL, $groups[0]) as $y => $line) {
    [$k, $v] = explode(': ', $line);
    $wires[$k] = (int) $v;
}

$instructions = [];
foreach (explode(PHP_EOL, $groups[1]) as $y => $line) {
    preg_match('/(\w{3}) (AND|OR|XOR) (\w{3}) -> (\w{3})/', $line, $matches);
    if (isset($wires[$matches[1]]) && isset($wires[$matches[3]])) {
        // Do calc
        $ans = match($matches[2]) {
            "AND" => $wires[$matches[1]] && $wires[$matches[3]],
            "OR" => $wires[$matches[1]] || $wires[$matches[3]],
            "XOR" => $wires[$matches[1]] xor $wires[$matches[3]],
        };
        $wires[$matches[4]] = (int) $ans;
    } else {
        $instructions[] = $line;
    }
}

while (count($instructions)) {
    foreach ($instructions as $i => $instruction) {
        preg_match('/(\w{3}) (AND|OR|XOR) (\w{3}) -> (\w{3})/', $instruction, $matches);
        if (isset($wires[$matches[1]]) && isset($wires[$matches[3]])) {
            // Do calc
            $ans = match ($matches[2]) {
                "AND" => $wires[$matches[1]] && $wires[$matches[3]],
                "OR" => $wires[$matches[1]] || $wires[$matches[3]],
                "XOR" => $wires[$matches[1]] xor $wires[$matches[3]],
            };
            $wires[$matches[4]] = (int) $ans;
            unset($instructions[$i]);
        }

    }
}
krsort($wires);
foreach ($wires as $k => $wire) {
    if (str_starts_with($k, 'z')) {
        echo $wire;
    }
}
d($instructions);

