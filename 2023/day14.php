<?php

require 'common.php';

$grid = get_input(14, false);

for ($i = 0; $i < strlen($grid[0]); $i++) {
    $open = 11;
    for ($r = 0; $r < count($grid) - 1; $r++) {
        $ch = $grid[$r][$i];
        if ($ch === '.') {
            for ($next = $r + 1; $next < count($grid); $next++) {
                // find next O and move it down
                if ($grid[$next][$i] === 'O') {
                    echo "Swapping $r with $next" . PHP_EOL;
                    $grid[$r][$i] = 'O';
                    $grid[$next][$i] = '.';
                    break;
                }
                if ($grid[$next][$i] === '#') {
                    $r = $next;
                    break;
                }
            }
        }
    }
    d($open);
    display_grid($grid);
}

$sum = 0;
foreach ($grid as $k => $line) {
    $count = count_chars($line, 1);
    d($count);
    $count = $count[79] ?? 0;
    echo ($count * (count($grid) - $k)) . PHP_EOL;
    $sum += $count * (count($grid) - $k);
}

d($sum);
function display_grid($grid) {
    foreach ($grid as $line) {
        echo "$line" . PHP_EOL;
    }
}

// 50
// 18
// 32
// 21
// 12
// 3
