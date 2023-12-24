<?php

require 'common.php';

$lines = get_input(16, false);

$grid = [];
$energized = [];
foreach ($lines as $line) {
    $grid[] = str_split($line);
}

$x = 0;
$y = 0;
$direction = EAST;
move($x, $y, $direction);

// p2
$max_energy = 0;
for ($col = 0; $col < count($grid[0]); $col++) {
    $x = $col;
    $y = 0;
    $direction = SOUTH;
    $energized = [];
    move($x, $y, $direction);
    if (count($energized) > $max_energy) {
        $max_energy = count($energized);
    }
    echo "Column $col going $direction has " . count($energized) . PHP_EOL;
}
for ($col = 0; $col < count($grid[0]); $col++) {
    $x = $col;
    $y = count($grid) - 1;
    $direction = NORTH;
    $energized = [];
    move($x, $y, $direction);
    if (count($energized) > $max_energy) {
        $max_energy = count($energized);
    }
    echo "Column $col going $direction has " . count($energized) . PHP_EOL;
}
for ($row = 0; $row < count($grid); $row++) {
    $x = 0;
    $y = $row;
    $direction = EAST;
    $energized = [];
    move($x, $y, $direction);
    if (count($energized) > $max_energy) {
        $max_energy = count($energized);
    }
    echo "Column $col going $direction has " . count($energized) . PHP_EOL;
}
for ($row = 0; $row < count($grid); $row++) {
    $x = count($grid[0]) - 1;
    $y = $row;
    $direction = WEST;
    $energized = [];
    move($x, $y, $direction);
    if (count($energized) > $max_energy) {
        $max_energy = count($energized);
    }
    echo "Column $col going $direction has " . count($energized) . PHP_EOL;
}

dd($max_energy);
function move($x, $y, $direction) {
    global $grid;
    global $energized;

    if (! inbounds($x, $y, count($grid[0]), count($grid))) {
        return;
    }

    if (isset($energized["$x,$y"])) {
        if (in_array($direction, $energized["$x,$y"])) {
            return;
        } else {
            $energized["$x,$y"][] = $direction;
        }
    } else {
        $energized["$x,$y"][] = $direction;
    }



    $current = $grid[$y][$x];
    switch ($current) {
        case '|': // do something
            if ($direction === EAST || $direction === WEST) {
                // split
                move($x, $y + 1, SOUTH);
                move($x, $y - 1, NORTH);
            } else {
                switch ($direction) {
                    case NORTH:
                        move($x, $y - 1, NORTH);
                        break;
                    case SOUTH:
                        move($x, $y + 1, SOUTH);
                        break;
                }
            }
            break;
        case '-':
            if ($direction === EAST || $direction === WEST) {
                switch ($direction) {
                    case EAST:
                        move($x + 1, $y, EAST);
                        break;
                    case WEST:
                        move($x - 1, $y, WEST);
                        break;
                }
            } else {
                // split
                move($x - 1, $y, WEST);
                move($x + 1, $y, EAST);
            }
            break;
        case '/':
            switch ($direction) {
                case NORTH:
                    // go east
                    move($x + 1, $y, EAST);
                    break;
                case EAST:
                    // go north
                    move($x, $y - 1, NORTH);
                    break;
                case SOUTH:
                    // go west
                    move($x - 1, $y, WEST);
                    break;
                case WEST:
                    // go south
                    move($x, $y + 1, SOUTH);
                    break;
            }
            break;
        case '\\':
            switch ($direction) {
                case NORTH:
                    // go west
                    move($x - 1, $y, WEST);
                    break;
                case EAST:
                    // go south
                    move($x, $y + 1, SOUTH);
                    break;
                case SOUTH:
                    // go east
                    move($x + 1, $y, EAST);
                    break;
                case WEST:
                    // go north
                    move($x, $y - 1, NORTH);
                    break;
            }
            break;
        case '.':
            switch ($direction) {
                case NORTH:
                    move($x, $y - 1, NORTH);
                    break;
                case EAST:
                    move($x + 1, $y, EAST);
                    break;
                case SOUTH:
                    move($x, $y + 1, SOUTH);
                    break;
                case WEST:
                    move($x - 1, $y, WEST);
                    break;
            }
    }
}

dd(count($energized));
