<?php

require 'common.php';

$lines = get_input(17, false);

$grid = [];

foreach ($lines as $y => $line) {
    $grid[] = str_split($line);
}

$seen = [];
$pq = new MinPriorityQueue();
$pq->setExtractFlags(\SplPriorityQueue::EXTR_BOTH);
$pq->insert([0, 0, 0, 0, 0], 0);

while (! $pq->isEmpty()) {
    // [$heat_loss, $r, $c, $dr, $dc, $n] = pop($priority_queue);
    $q = $pq->extract();
    [$r, $c, $dr, $dc, $n] = $q['data'];
    $hl = $q['priority'];

    if ($r === count($grid) - 1 && $c === count($grid[0]) - 1 && $n >= 4) {
        d($hl);
        break;
    }

    // if ($r < 0 || $r >= count($grid) || $c < 0 || $c >= count($grid[0])) {
    //     continue;
    // }

    if (in_array([$r, $c, $dr, $dc, $n], $seen)) {
        continue;
    }

    $seen[] = [$r, $c, $dr, $dc, $n];

    if ($n < 10 && [$dr, $dc] !== [0, 0]) {
        $nr = $r + $dr;
        $nc = $c + $dc;
        if ($nr >= 0 && $nr < count($grid) && $nc >= 0 && $nc < count($grid[0])) {
            $pq->insert([$nr, $nc, $dr, $dc, $n + 1], $hl + $grid[$nr][$nc]);
        }
    }

    if ($n >= 4 || [$dr, $dc] === [0, 0]) {
        foreach ($adjacents as $dir) {
            [$ndr, $ndc] = $dir;
            if ([$ndr, $ndc] !== [$dr, $dc] && [$ndr, $ndc] !== [-$dr, -$dc]) {
                $nr = $r + $ndr;
                $nc = $c + $ndc;
                if ($nr >= 0 && $nr < count($grid) && $nc >= 0 && $nc < count($grid[0])) {
                    $pq->insert([$nr, $nc, $ndr, $ndc, 1], $hl + $grid[$nr][$nc]);
                }
            }
        }
    }
}
