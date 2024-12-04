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
    //echo "$parent -> $child" . PHP_EOL;
}

fclose($file);

// print_r($instructions);
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

$workers = [];
$completed = [];
$seconds = 0;
$steps = str_split($order);
$workingSteps = [];
$availableWorkers = [0, 1, 2, 3, 4];

while (count($completed) !== count($steps)) {
    foreach ($workers as $k => $worker) {
        if ($worker['time_left'] > 1) {
            $workers[$k]['time_left']--;
        } else {
            $completed[] = $worker['step'];
            unset($workingSteps[array_search($worker['step'], $workingSteps)]);
            $availableWorkers[] = $k;
            unset($workers[$k]);
        }
    }

    for ($i = 0; $i < 5; $i++) {
        // check if worker is available
        if (!isset($workers[$i])) {
            // check if step is available
            foreach ($steps as $step) {
                if (in_array($step, $workingSteps) || in_array($step, $completed)) {
                    continue;
                }
                if (isset($waitfor[$step])) {
                    $needsCompleted = $waitfor[$step];
                    $completedCount = count(array_intersect($needsCompleted, $completed));
                    if ($completedCount == count($needsCompleted)) {
                        // THIS STEP IS READY TO START WORKING
                        $workers[$i] = ['step' => $step, 'time_left' => 60 + ord($step) - 64];
                        $workingSteps[] = $step;
                        break;
                    }
                } else {
                    // Don't need to wait for anything go go go
                    $workers[$i] = ['step' => $step, 'time_left' => 60 + ord($step) - 64];
                    $workingSteps[] = $step;
                    break;
                }
            }
        }
    }

    show($seconds, $workers, $completed);

    $seconds++;
    if ($seconds > 1200) {
        break;
    }
    //break;
}

function show($seconds, $workers, $completed)
{
    printf("%4d ", $seconds);
    for ($i = 0; $i < 5; $i++) {
        //if (isset($workers[$i]))
        echo ' ' . (isset($workers[$i]) ? $workers[$i]['step'] : '.') . ' ';
    }
    echo '  ' . implode("", $completed) . PHP_EOL;
}
