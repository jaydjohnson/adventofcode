<?php

const ROCKY = 0;
const WET = 1;
const NARROW = 2;
const TORCH = 0;
const CLIMBING = 1;
const NEITHER = 2;


$depth = 6969;
$target = [9, 796];
$erosionLevels = [];
$typeMap = [];
createMap($erosionLevels, $typeMap, $depth, $target);
$costs = findTarget($typeMap, $target);
echo $costs[$target[1]][$target[0]][TORCH]['cost'] . PHP_EOL;

function findTarget($map, $target)
{
    $validItems = [ROCKY => [TORCH, CLIMBING], WET => [CLIMBING, NEITHER], NARROW => [TORCH, NEITHER]];
    $validRegions = [TORCH => [ROCKY, NARROW], CLIMBING => [ROCKY, WET], NEITHER => [WET, NARROW]];
    $costs = [];
    $queue = new SplPriorityQueue();
    $queue->setExtractFlags(SplPriorityQueue::EXTR_BOTH);
    $queue->insert([0, 0, TORCH, []], 0);

    while (!$queue->isEmpty()) {
        $q = $queue->extract();
        list($x, $y, $tool, $path) = $q['data'];
        $cost = abs($q['priority']);

        // already visited
        if (isset($costs[$y][$x][$tool])) {
            continue;
        }

        $costs[$y][$x][$tool] = ['cost' => $cost, 'path' => $path];
        $type = $map[$y][$x];

        foreach ([[$x, $y-1], [$x - 1, $y], [$x + 1, $y], [$x, $y + 1]] as $p) {
            [$px, $py] = $p;

            if (!isset($map[$py][$px])) {
                continue;
            }

            $pType = $map[$py][$px];

            if (in_array($tool, $validItems[$pType]) && ! isset($costs[$py][$px][$tool])) {
                $queue->insert([$px, $py, $tool, $path], -($cost + 1));
            }
        }

        // also try chaging tool and visiting ourselves
        foreach ($validItems[$type] as $t) {
            if ($tool != $t && ! isset($costs[$y][$x][$t])) {
                $queue->insert([$x, $y, $t, $path], -($cost + 7));
            }
        }
    }
    return $costs;
}

function createMap(&$erosionLevels, &$typeMap, $depth, $target)
{
    for ($y = 0; $y < $target[1] + 100; $y++) {
        for ($x = 0; $x < $target[0] + 100; $x++) {
            if (($x == 0 && $y == 0) || ($x == $target[0] && $y == $target[1])) {
                $elevel = (0 + $depth) % 20183;
            } elseif ($x == 0 && $y > 0) {
                $elevel = (($y * 48271) + $depth) % 20183;
            } elseif ($y == 0 && $x > 0) {
                $elevel = (($x * 16807) + $depth) % 20183;
            } else {
                $elevel = (($erosionLevels[$y][$x-1] * $erosionLevels[$y-1][$x]) + $depth) % 20183;
            }
            
            $erosionLevels[$y][$x] = $elevel;
            $type = $elevel % 3;
            // switch ($type) {
            //     case 0: $type = "."; break;
            //     case 1: $type = "="; break;
            //     case 2: $type = "|"; break;
            // }
            $typeMap[$y][$x] = $type;
        }
    }
}

function sumMap($map, $target)
{
    $sum = 0;
    for ($y = 0; $y <= $target[1]; $y++) {
        for ($x = 0; $x <= $target[0]; $x++) {
            $sum += $map[$y][$x];
        }
    }
    echo $sum;
}
