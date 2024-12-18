<?php
$__CLI['long'] = ['plain', 'nocolour', 'full', 'noclear', 'sleep:'];
$__CLI['extrahelp'] = [];
$__CLI['extrahelp'][] = '      --plain              Don\'t include ansi in debug output';
$__CLI['extrahelp'][] = '      --nocolour           Don\'t include colour debug output';
$__CLI['extrahelp'][] = '      --full               Draw all steps';
$__CLI['extrahelp'][] = '      --sleep <#>          Time between steps in full mode.';
$__CLI['extrahelp'][] = '      --noclear            Don\'t clear screen in full mode';

require_once(dirname(__FILE__) . '/common.php');
$input = getInputLines();

$grid = [];
$grid[0][500] = '+';

$minX = $maxX = 500;
$minY = PHP_INT_MAX;
$maxY = PHP_INT_MIN;

foreach ($input as $details) {
    preg_match('#([xy])=([0-9]+), ([xy])=([0-9]+)..([0-9]+)#SADi', $details, $m);
    foreach (range($m[4], $m[5]) as $z) {
        $bit = [];
        $bit[$m[1]] = $m[2];
        $bit[$m[3]] = $z;

        $grid[$bit['y']][$bit['x']] = '#';

        $minY = min($bit['y'], $minY);
        $minX = min($bit['x'], $minX);
        $maxY = max($bit['y'], $maxY);
        $maxX = max($bit['x'], $maxX);
    }
}


function draw($hX = null, $hY = null)
{
    global $minY, $minX, $maxY, $maxX, $grid, $__CLIOPTS;

        // 0 not minY so that we can include the water source.
    $drawMinY = 0;
    $drawMaxY = $maxY;

    if ($hX != null && $hY != null) {
        if (!isset($__CLIOPTS['noclear'])) {
            echo "\033[2J";
            echo "\033[;H";
        }
        $viewport = 40;

        $drawMinY = ($hY - floor($viewport / 2));
        $drawMaxY = ($hY + floor($viewport / 2));
    }

    for ($y = $drawMinY; $y <= $drawMaxY; $y++) {
        for ($x = $minX - 1; $x <= $maxX + 1; $x++) {

            if ($hX == $x && $hY == $y) {
                echo 'X';
            } else {
                $bit = get($x, $y);

                if (isset($__CLIOPTS['plain'])) {
                    echo $bit;
                } else {
                    if ($bit == '#') {
                        if (get($x + 1, $y) != '#' && get($x - 1, $y) == '#' && get($x, $y + 1) == '#' && get($x, $y - 1) != '#') {
                                // Top Right Corner.
                            echo '┐';
                        } else if (get($x + 1, $y) == '#' && get($x - 1, $y) != '#' && get($x, $y + 1) == '#' && get($x, $y - 1) != '#') {
                                // Top Left Corner
                            echo '┌';
                        } else if (get($x + 1, $y) != '#' && get($x - 1, $y) == '#' && get($x, $y + 1) != '#' && get($x, $y - 1) == '#') {
                                // Bottom Right Corner.
                            echo '┘';
                        } else if (get($x + 1, $y) == '#' && get($x - 1, $y) != '#' && get($x, $y + 1) != '#' && get($x, $y - 1) == '#') {
                                // Bottom Left Corner
                            echo '└';
                        } else if (get($x, $y + 1) != '#' && get($x, $y - 1) != '#') {
                                // Top/Bottom Edge
                            echo '─';
                        } else if (get($x + 1, $y) != '#' && get($x - 1, $y) != '#') {
                                // Left/Right Edge
                            echo '│';
                        } else {
                                // Unknown.
                            echo '@';
                        }

                    } else if ($bit == '.') {
                        echo ' ';
                    } else {
                        if (!isset($__CLIOPTS['nocolour'])) {
                            echo "\033[1;34m";
                        }
                        if ($bit == '|') {
                            echo '░';
                        }
                        if ($bit == '~') {
                            echo '█';
                        }
                        if ($bit == '+') {
                            echo '▄';
                        }
                        if (!isset($__CLIOPTS['nocolour'])) {
                            echo "\033[0m";
                        }
                    }
                }
            }
        }
        echo "\n";
    }
    echo "\n";

    if ($hX != null && $hY != null) {
        for ($x = $minX - 1; $x <= $maxX + 1; $x++) {
            echo '┄';
        }
        echo "\n";
    }
    usleep(isset($__CLIOPTS['sleep']) ? $__CLIOPTS['sleep'] : 50000);
}

function get($x, $y)
{
    global $grid;

    return isset($grid[$y][$x]) ? $grid[$y][$x] : '.';
}

function set($x, $y, $c)
{
    global $grid;
    $grid[$y][$x] = $c;
}

    // Can this space pour water?
    // A space can pour water if it is empty or unsettled.
function canPour($x, $y)
{
    return get($x, $y) == '|' || get($x, $y) == '.';
}

function pour($x, $y)
{
    global $maxY, $__CLIOPTS;

        // Are we a water source?
        // Start pouring below us instead.
    if (get($x, $y) == '+') {
        pour($x, $y + 1);
        return;
    }

        // If we are below the boundary, don't do anything.
    if ($y > $maxY) {
        return;
    }

        // If we are not a valid location to pour from, do nothing.
    if (!canPour($x, $y)) {
        return;
    }

    if (isset($__CLIOPTS['full'])) {
        draw($x, $y);
    }

        // If the space below us isn't pourable, we need to try and overflow.
    if (!canPour($x, $y + 1)) {
            // Find the furthest point left we can flow before we start pouring
            // down again.
        $left = $x;
        while (canPour($left, $y) && !canPour($left, $y + 1)) {
            set($left, $y, '|');
            $left--;
        }

            // Same again Right.
        $right = $x;
        while (canPour($right, $y) && !canPour($right, $y + 1)) {
            set($right, $y, '|');
            $right++;
        }

            // Now if we can pour left or right, do so.
        if (canPour($right, $y + 1) || canPour($left, $y + 1)) {
            pour($left, $y);
            pour($right, $y);

            // If we didn't find anything pourable either side of us, then we
            // are settling.
        } else if (get($left, $y) == '#' && get($right, $y) == '#') {
                // Settle
            for ($x2 = $left + 1; $x2 < $right; $x2++) {
                set($x2, $y, '~');
            }
        }

        // Otherwise, if we are sand, then we become unsettled and start
        // pouring from below us.
    } else if (get($x, $y) == '.') {
        set($x, $y, '|');
        pour($x, $y + 1);

            // Has the square below us become settled, if so, try pouring
            // from here again in case we are overflowing.
        if (get($x, $y + 1) == '~') {
            pour($x, $y);
        }
    }
}

pour(500, 0);
if (isDebug()) {
    draw();
}

$part1 = $part2 = 0;
for ($y = $minY; $y <= $maxY; $y++) {
    for ($x = $minX - 2; $x <= $maxX + 2; $x++) {
        if (get($x, $y) == '~') {
            $part1++;
            $part2++;
        } else if (get($x, $y) == '|') {
            $part1++;
        }
    }
}

echo 'Part 1: ', $part1, "\n";
echo 'Part 2: ', $part2, "\n";
