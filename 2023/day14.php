<?php

require 'common.php';

$grid = get_input(14, true);

$prev_sum = 0;
$cycle = 0;
$seen = [];
while (true) {
    tilt_up();
    tilt_left();
    tilt_down();
    tilt_right();
    $cycle++;
    // $sum = sum_grid();
    display_grid($grid);
    if (in_array($grid, $seen)) {
        break;
    }
    $seen[] = $grid;
};
$cycle--;
d(count($seen));
$first = array_search($grid, $seen, true);
echo "Broke cycle $cycle first: $first" . PHP_EOL;
display_grid($grid);

$grid = $seen[((1000000000 - $first) % ($cycle - $first)) + $first];
sum_grid();

// for ($cycle = $cycle; $cycle < 20; $cycle++) {
//     tilt_up();
//     tilt_left();
//     tilt_down();
//     tilt_right();
//     $sum = sum_grid();
//     display_grid($grid);
// }

function tilt_up() {
    global $grid;

    for ($i = 0; $i < strlen($grid[0]); $i++) {
        for ($r = 0; $r < count($grid) - 1; $r++) {
            $ch = $grid[$r][$i];
            if ($ch === '.') {
                for ($next = $r + 1; $next < count($grid); $next++) {
                    // find next O and move it down
                    if ($grid[$next][$i] === 'O') {
                        // echo "Swapping $r with $next" . PHP_EOL;
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
    }
    // echo "tilt up" . PHP_EOL;
    // display_grid($grid);
    // echo PHP_EOL;
}

function tilt_right() {
    global $grid;

    for ($r = 0; $r < count($grid); $r++) {
        for ($i = strlen($grid[0]) - 1; $i > 0; $i--) {
            $ch = $grid[$r][$i];
            if ($ch === '.') {
                for ($next = $i - 1; $next >= 0; $next--) {
                    // find next O and move it down
                    if ($grid[$r][$next] === 'O') {
                        // echo "Swapping $r with $next" . PHP_EOL;
                        $grid[$r][$i] = 'O';
                        $grid[$r][$next] = '.';
                        break;
                    }
                    if ($grid[$r][$next] === '#') {
                        $i = $next;
                        break;
                    }
                }
            }
        }
    }
    // echo "tilt right" . PHP_EOL;
    // display_grid($grid);
    // echo PHP_EOL;
}

function tilt_down() {
    global $grid;

    for ($i = 0; $i < strlen($grid[0]); $i++) {
        for ($r = count($grid) - 1; $r >= 0; $r--) {
            $ch = $grid[$r][$i];
            if ($ch === '.') {
                for ($next = $r - 1; $next >= 0; $next--) {
                    // find next O and move it down
                    if ($grid[$next][$i] === 'O') {
                        // echo "Swapping $r with $next" . PHP_EOL;
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
    }
    // echo "tilt down" . PHP_EOL;
    // display_grid($grid);
    // echo PHP_EOL;
}

function tilt_left() {
    global $grid;

    for ($r = 0; $r < count($grid) - 1; $r++) {
        for ($i = 0; $i < strlen($grid[0]) - 1; $i++) {
            $ch = $grid[$r][$i];
            if ($ch === '.') {
                for ($next = $i + 1; $next < count($grid); $next++) {
                    // find next O and move it down
                    if ($grid[$r][$next] === 'O') {
                        // echo "Swapping $r with $next" . PHP_EOL;
                        $grid[$r][$i] = 'O';
                        $grid[$r][$next] = '.';
                        break;
                    }
                    if ($grid[$r][$next] === '#') {
                        $i = $next;
                        break;
                    }
                }
            }
        }
    }
    // echo "tilt left" . PHP_EOL;
    // display_grid($grid);
    // echo PHP_EOL;
}

function sum_grid() {
    global $grid;

    $sum = 0;
    foreach ($grid as $k => $line) {
        $count = count_chars($line, 1);
        $count = $count[79] ?? 0;
        $sum += $count * (count($grid) - $k);
    }
    echo "grid sum $sum" . PHP_EOL;
    return $sum;
}

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
// .....#....
// ....#...O#
// .....##...
// ..O#......
// .....OOO#.
// .O#...O#.#
// ....O#...O
// .......OOO
// #...O###.O
// #.OOO#...O