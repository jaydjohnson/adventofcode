<?php


$file = fopen('day7.txt', 'r');
$instructions = [];
$waitfor = [];

while (($line = fgets($file)) !== false) {
    preg_match('/ ([A-Z]) must be finished before step ([A-Z]) /', $line, $matches);
    $parent = $matches[1];
    $child = $matches[2];
    $instructions[$parent][] = $child;
    $waitfor[$child][] = $parent;
    echo "$parent -> $child" . PHP_EOL;
}

fclose($file);

print_r($instructions);
print_r($waitfor);

$order = '';
// Find parents that are not anyones children
while (count($instructions) > 0) {
    $not_a_child = [];
    foreach ($instructions as $parent => $children) {
        $is_child = false;
        foreach ($instructions as $subparent => $subchildren) {
            if ($subparent === $parent) {
                continue;
            }
            foreach ($subchildren as $subchild) {
                if ($subchild === $parent) {
                    $is_child = true;
                    break;
                }
            }
        }
        if (!$is_child) {
//            echo $parent . " is not a child" . PHP_EOL;
            if (!in_array($parent, $not_a_child, true)) {
                $not_a_child[] = $parent;
            }
        }
    }

// First letter (in the alphabet) goes first
    sort($not_a_child);
    $this_child = reset($not_a_child);
    $order .= $this_child;
// If this is the last element make sure to add all children
    if (count($instructions) === 1) {
        foreach (reset($instructions) as $item) {
            $order .= $item;
        }
    }
    unset($not_a_child[0]);
    unset($instructions[$this_child]);
}
echo "Order (part #1): " . $order . PHP_EOL;
