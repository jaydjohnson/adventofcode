<?php
include __DIR__ . '/utils.php';

gc_disable();
ini_set('memory_limit', '1024M');

$file = fopen('day20.txt', 'r');
$directions = [];

while (($line = fgets($file)) !== false) {
    $directions[] = trim($line);
}

//echo $directions[1];

$input_path = $directions[2];
$mapped = [
    '0,0' => 0,
];

$current = [0,0];
$dist = 0;
$branches = [];

$max_steps = strlen($input_path);

for ($i = 0; $i < $max_steps; $i ++) {
    $step = $input_path[$i];

    switch ($step) {
        case 'N':
        case 'E':
        case 'S':
        case 'W':
            // take step
            $dist++;
            $current = nextFrom($current, $step);
            $key = "{$current[0]},{$current[1]}";
            if (isset($mapped[$key])) {
                if ($mapped[$key] > $dist) {
                    $mapped[$key] = $dist;
                }
            }
            else {
                $mapped[$key] = $dist;
            }
            break;

        case '(':
            // start branch
            $branches[] = [$current, $dist];
            break;

        case '|':
            // backtrack to start of branch branch
            [$current, $dist] = $branches[ count($branches) - 1 ];
            break;

        case ')':
            // end branch
            [$current, $dist] = array_pop($branches);
            break;
    }
}

$furthest = array_reduce(
    $mapped,
    function ($carry, $item) {
        if (isset($carry) && $carry > $item) {
            return $carry;
        }
        return $item;
    }
);

echo "Furthest room: {$furthest}\n";

$distant_rooms = array_filter(
    $mapped,
    function ($dist) {
        return $dist >= 1000;
    }
);
$num_rooms = count($distant_rooms);
echo "Distant rooms: {$num_rooms}\n";

function nextFrom($pos, string $dir)
{
    switch ($dir) {
        case 'N': $pos[1]--; break;
        case 'S': $pos[1]++; break;
        case 'E': $pos[0]--; break;
        case 'W': $pos[0]++; break;
    }

    return $pos;
}
