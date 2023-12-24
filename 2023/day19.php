<?php

require 'common.php';

// $lines = get_input(19, true);
$parts = file_get_contents('day19.in');
[$flows, $ratings] = explode("\n\n", $parts);
$workflows = [];

foreach (explode("\n", $flows) as $line) {
    [$name, $str] = explode("{", $line);
    $raw_rules = explode(',', substr($str, 0, -1));
    foreach ($raw_rules as $raw_rule) {
        if (str_contains($raw_rule, ":")) {
            $workflows[$name]['rules'][] = [
                'cat' => substr($raw_rule, 0, 1),
                'cond' => substr($raw_rule, 1, 1),
                'value' => explode(':', substr($raw_rule, 2))[0],
                'goto' => explode(':', substr($raw_rule, 2))[1],
            ];
        } else {
            $workflows[$name]['else'] = $raw_rule;
        }
    }
}

$sum = 0;
foreach (explode("\n", $ratings) as $line) {
    $item = [];
    foreach (explode(",", substr($line, 1, -1)) as $segment) {
        [$cat, $value] = explode('=', $segment);
        $item[$cat] = (int) $value;
    };

    $status = check_part('in', $item);
    if ($status === 'A') {
        $sum += array_sum($item);
    }
}

d($sum);


$sum = 0;
$ranges = [
    'x' => [1 ,4000],
    'm' => [1, 4000],
    'a' => [1, 4000],
    's' => [1, 4000],
];

$sum = check_ranges('in', $ranges);
d($sum);

function check_part($rule, $item) {
    global $workflows;

    if (in_array($rule, ['A', 'R'])) {
        return $rule;
    }

    $flows = $workflows[$rule]['rules'];
    foreach ($flows as $flow) {
        if ($flow['cond'] === '<') {
            if ($item[$flow['cat']] < $flow['value']) {
                return check_part($flow['goto'],$item);
            }
        } else {
            if ($item[$flow['cat']] > $flow['value']) {
                return check_part($flow['goto'], $item);
            }
        }
    }
    return check_part($workflows[$rule]['else'], $item);
}

function check_ranges($rule, $ranges) {
    global $workflows;

    if ($rule === 'R') {
        return 0;
    }
    if ($rule === 'A') {
        $product = 1;
        foreach ($ranges as $range) {
            [$lo, $hi] = $range;
            $product *= $hi - $lo + 1;
        }
        return $product;
    }

    $total = 0;

    $flows = $workflows[$rule]['rules'];
    $rules_matched = true;
    foreach ($flows as $flow) {
        $key = $flow['cat'];
        [$lo, $hi] = $ranges[$key];
        $n = (int) $flow['value'];
        if ($flow['cond'] === '<') {
            $true = [$lo, min($n - 1, $hi)];
            $false = [max($n, $lo), $hi];
        } else {
            $true = [max($n + 1, $lo), $hi];
            $false = [$lo, min($n, $hi)];
        }

        if ($true[0] <= $true[1]) {
            $copy = $ranges;
            $copy[$key] = $true;
            $total += check_ranges($flow['goto'], $copy);
        }

        if ($false[0] <= $false[1]) {
            $ranges[$key] = $false;
        } else {
            $rules_matched = false;
            break;
        }
    }

    if ($rules_matched) {
        echo 'default rule' . PHP_EOL;
        $total += check_ranges($workflows[$rule]['else'], $ranges);
    }

    return $total;
}
