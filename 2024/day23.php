<?php

require 'common.php';

$lines = get_input(23, true);

$pairs = [];
$connections = [];
foreach ($lines as $y => $line) {
    $pcs = explode('-', $line);
    $pairs[$pcs[0]] = $pcs[1];
    $pairs[$pcs[1]] = $pcs[0];
    $connections[$pcs[0]][] = $pcs[1];
    $connections[$pcs[1]][] = $pcs[0];
}

dd($connections['ka']);
$matches = [];
foreach ($connections as $pc1 => $conns) {
    l("PC1: $pc1");
    foreach ($conns as $pc2) {
        l("PC2: $pc2");
        foreach ($connections[$pc2] as $pc3) {
            l("PC3: $pc3");
            foreach ($connections[$pc3] as $pc1b) {
                if ($pc1b === $pc1) {
                    l("found match: $pc1, $pc2, $pc3");
                    $tmp = [$pc1, $pc2, $pc3];
                    sort($tmp);
                    if (!in_array(implode(',', $tmp), $matches)) {
                        $matches[] = implode(',', $tmp);
                    }
                }
            }

        }
    }
}

d($matches);

$sum = 0;
foreach ($matches as $match) {
    if (str_starts_with($match, 't') || str_contains($match, ',t')) {
        $sum++;
    }
}

l($sum);