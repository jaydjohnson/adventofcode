<?php

$bots = [];
$file = fopen("day23.txt", "r");
while (($line = fgets($file)) !== false) {
    preg_match("/pos=<(-?\d+),(-?\d+),(-?\d+)>, r=(\d+)/", $line, $matches);
    array_shift($matches);
    $bots[] = $matches;
}

$strongestBot = getStrongest($bots);
$inRange = countBots($bots, $strongestBot);
echo "in range: $inRange" . PHP_EOL;
//mapRange($bots);
getMost($bots);
// 16777165
// 47141438 - python
function getMost($bots)
{
    $queue = new SplPriorityQueue();
    $queue->setExtractFlags(SplPriorityQueue::EXTR_BOTH);
    foreach ($bots as $bot) {
        $d = abs($bot[0]) + abs($bot[1]) + abs($bot[2]);
        $queue->insert([max(0, $d - $bot[3])], 1);
        $queue->insert([$d + $bot[3] + 1], -1);
    }

    $count = 0;
    $maxCount = 0;
    $result = 0;

    while (!$queue->isEmpty()) {
        $q = $queue->extract();
        $count += $q['priority'];
        if ($count > $maxCount) {
            $result = $q['data'][0];
            $maxCount = $count;
        }
    }

    echo "Result: $result" . PHP_EOL;

    return $result;
}

function mapRange($bots)
{
    $minx = $maxx = $miny = $maxy = $minz = $maxz = null;
    foreach ($bots as $bot) {
        if ($minx == null || $bot[0] < $minx) {
            $minx = $bot[0];
        }
        if ($maxx == null || $bot[0] > $maxx) {
            $maxx = $bot[0];
        }
        if ($miny == null || $bot[1] < $miny) {
            $miny = $bot[1];
        }
        if ($maxy == null || $bot[1] > $maxy) {
            $maxy = $bot[1];
        }
        if ($minz == null || $bot[2] < $minz) {
            $minz = $bot[2];
        }
        if ($maxz == null || $bot[2] > $maxz) {
            $maxz = $bot[2];
        }

    }
    echo "$minx/$maxx (" . ($maxx-$minx) ."), $miny/$maxy (" . ($maxy-$miny) . "), $minz/$maxz (" . ($maxz-$minz) . ")" . PHP_EOL;
}

function countBots($bots, $strongest)
{
    $mainBot = $bots[$strongest];
    $sumBots = 0;
    foreach ($bots as $bot) {
        if (calculateDistance($mainBot, $bot) <= $mainBot[3]) {
            $sumBots++;
        }
    }

    return $sumBots;
}

function calculateDistance($start, $target)
{
    $distance = abs($target[0] - $start[0]) + abs($target[1] - $start[1]) + abs($target[2] - $start[2]);
    return $distance;
}

function getStrongest($bots)
{
    $maxRadiusKey = 0;
    foreach ($bots as $k => $bot) {
        if ($bot[3] > $bots[$maxRadiusKey][3]) {
            $maxRadiusKey = $k;
        }
    }
    return $maxRadiusKey;
}
