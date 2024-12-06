<?php

include 'common.php';

$lines = get_input(5, true);

$pages = [];
$updates = [];
foreach ($lines as $y => $line) {
    if (str_contains($line, '|')) {
        $nums = explode('|', $line);
        $pages[$nums[0]][] = $nums[1];
    } elseif (str_contains($line, ',')) {
        $updates[] = explode(',', $line);
    }
}

v($pages);
$sum = 0;
$incorrects = [];
foreach ($updates as $i => $update) {
    $found = true;
    for($j=0; $j < count($update)-1; $j++) {
        for($k=$j+1; $k < count($update); $k++) {
            if (!isset($pages[$update[$j]]) || !in_array($update[$k], $pages[$update[$j]])) {
                $found = false;
                break;
            }
        }
    }
    if ($found) {
        v($update);
        $sum += $update[floor(count($update)/2)];
    } else {
        $incorrects[] = $update;
    }
}
v($sum);

foreach ($incorrects as $incorrect) {
    v($incorrect);
    for ($i = 0; $i < count($incorrect); $i++) {
        if (isset($pages[$incorrect[$i]])) {
            
        }
    }
}
