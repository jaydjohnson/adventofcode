<?php

require 'common.php';

$file = file_get_contents('day13.ex');

$raw_patterns = explode("\n\n", $file);

foreach ($raw_patterns as $i => $r_pattern) {
    $lines = explode("\n", $r_pattern);
    foreach ($lines as $line) {
        $patterns[$i][] = str_split($line);
    };
}


foreach ($patterns as $pattern) {
    $v_match = 0;
    $h_match = 0;
    $row = $pattern[0];
    // check vertical
    for ($i = 1; $i < count($row); $i++) {
        $left = array_column($pattern, $i);
        $right = array_column($pattern, $i + 1);
        if ($left == $right) {
            // check others
            echo "found possible match on " . $i . ' and ' . $i + 1 . PHP_EOL;
            $diff = $i < (count($row) - 1) / 2 ? $i : count($row) - $i - 2;
            echo "$i with a diff: $diff" . PHP_EOL;
            for ($j = 1; $j <= $diff; $j++) {
                echo 'checking ' . $i - $j . ' and ' . $i + 1 + $j . PHP_EOL;
                $l[] = array_column($pattern, $i - $j);
                $r[] = array_column($pattern, $i + 1 + $j);
            }

            if ($l == $r) {
                echo "found true match on $i and " . $i + 1 . PHP_EOL;
                $v_match = $i + 1;
            }
        }
    }

    for ($i = 1; $i < count($pattern) - 2; $i++) {
        $top = [$pattern[$i], $pattern[$i - 1]];
        $bottom = [$pattern[$i + 1], $pattern[$i + 2]];
        if ($top == $bottom) {
            $h_match = $i + 1;
        }
    }
    d('found' . $v_match);
    d('found' . $h_match);
}
