<?php

require 'common.php';

$file = file_get_contents('day13.in');

$raw_patterns = explode("\n\n", $file);

foreach ($raw_patterns as $i => $r_pattern) {
    $lines = explode("\n", $r_pattern);
    foreach ($lines as $line) {
        $patterns[$i][] = str_split($line);
    };
}

$sum = 0;
foreach ($patterns as $k => $pattern) {
	echo " --- Checking pattern $k --- " . PHP_EOL;
    $v_match = 0;
    $h_match = 0;
    $row = $pattern[0];
    // check vertical
    for ($i = 0; $i < count($row); $i++) {
        $left = array_column($pattern, $i);
        $right = array_column($pattern, $i + 1);
        if ($left == $right) {
            // check others
            // echo "found possible match on " . $i . ' and ' . $i + 1 . PHP_EOL;
            $diff = $i < (count($row) - 1) / 2 ? $i : count($row) - $i - 2;
            // echo "$i with a diff: $diff" . PHP_EOL;
			$l = [];
			$r = [];
            for ($j = 1; $j <= $diff; $j++) {
                // echo 'checking ' . $i - $j . ' and ' . $i + 1 + $j . PHP_EOL;
                $l[] = array_column($pattern, $i - $j);
                $r[] = array_column($pattern, $i + 1 + $j);
            }

            if ($l == $r) {
                echo "found true match on $i and " . $i + 1 . PHP_EOL;
                $v_match = $i + 1;
				$sum += $v_match;
            }
        }
    }

    for ($i = 0; $i < count($pattern) - 1; $i++) {
        $top = $pattern[$i];
        $bottom = $pattern[$i + 1];
        if ($top == $bottom) {
			echo "found possible h_match on " . $i . ' and ' . $i + 1 . PHP_EOL;
			$diff = $i < (count($pattern) - 1) / 2 ? $i : count($pattern) - $i - 2;
			// echo "$i with a diff: $diff" . PHP_EOL;
			$t = [];
			$b = [];
			for ($j = 1; $j <= $diff; $j++) {
                // echo 'checking ' . $i - $j . ' and ' . $i + 1 + $j . PHP_EOL;
				// echo implode('', $pattern[$i - $j]) . PHP_EOL;
				// echo implode('', $pattern[$i + 1 + $j]) . PHP_EOL;
                $t[] = $pattern[$i - $j];
                $b[] = $pattern[$i + 1 + $j];
            }

			if ($t == $b) {
				echo "found true h_match on $i and " . $i + 1 . PHP_EOL;
                $h_match = $i + 1;
				$sum += $h_match * 100;
			}
        }
    }
}
d($sum);
