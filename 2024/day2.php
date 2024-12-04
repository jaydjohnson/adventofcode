<?php

include 'common.php';

$lines = get_input(2, false);

$safe = 0;
$safe2 = 0;
foreach ($lines as $y => $line) {
    $levels = explode(' ', $line);
    $dir = '';
    $is_safe = is_safe($levels);
    $safe += $is_safe ? 1 : 0;

    if (! $is_safe) {
        for ($i = 0; $i < count($levels); $i++) {
            $new_levels = array_diff_key($levels, [$i => 0]);
            // d($new_levels);
            if (is_safe(array_values($new_levels))) {
                echo "found new save\n";
                $safe2++;
                break;
            }
        }
    }
}
d($safe);
d($safe + $safe2);

function is_safe($items) {
    $diffs = [];
    for ($i = 0; $i < count($items) - 1; $i++) {
        $diffs[] = $items[$i] - $items[$i + 1];
    }
    $a = array_filter($diffs, function ($item) {
        return $item > 3 || $item < -3 || $item === 0;
    });
    if ((empty(array_filter($diffs, 'positives')) || empty(array_filter($diffs, 'negatives'))) && empty($a)) {
        return true;
    }
    return false;
}

function positives($item) {
    return $item > 0;
}

function negatives($item) {
    return $item < 0;
}
