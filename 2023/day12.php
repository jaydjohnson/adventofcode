<?php

/**
 * Sadly this isn't all mine, mostly taken from python example and ported to php I tried so hard... :'(
 */

require 'common.php';

$lines = get_input(12, false);



$sum = 0;
$sum2 = 0;
foreach ($lines as $line) {
    [$s, $g] = explode(' ', $line);
    $groups = explode(',', $g);
    $springs = $s . '.';

    $groups2 = explode(',', rtrim(str_repeat($g . ',', 5), ','));
    $springs5 = str_repeat($s . '?', 5);
    $springs2 = substr($springs5, 0, strlen($springs5) - 1) . '.';
    // echo $springs . PHP_EOL . implode(',', $groups) . PHP_EOL;
    echo " ----- new group ----- " . PHP_EOL;

    $cache = [];
    $s = count_patterns(0, 0, $springs, $groups);
    echo "SUM for part 1 group: $s" . PHP_EOL;
    $sum += $s;

    $cache = [];
    // echo "$springs2 - " . implode(',', $groups2) . PHP_EOL;
    $s2 = count_patterns(0, 0, $springs2, $groups2);
    echo "SUM for part 2 group: $s2" . PHP_EOL;
    $sum2 += $s2;
}

d($sum);
d($sum2);

function count_patterns($spring_pos, $group_num, $springs, $groups)
{
    global $cache;
    // echo "Starting Count " . $spring_pos . ' ' . $group_num . PHP_EOL;
    if ($group_num >= count($groups)) {
        if ($spring_pos < strlen($springs) && str_contains(substr($springs, $spring_pos), '#')) {
            return 0; // not a solution
        }
        return 1;
    }

    if ($spring_pos >= strlen($springs)) {
        return 0; // ran out of springs
    }

    if (isset($cache["$spring_pos,$group_num"])) {
        return $cache["$spring_pos,$group_num"];
    }

    $count = 0;
    $group_size = $groups[$group_num];
    //$current_group = substr($springs, $spring_pos, $group_size);
    $current_spring = $springs[$spring_pos];
    //$last_spring = $springs[$spring_pos + $group_size];

    // echo 'Starting: ' . $spring_pos . ' ' . $group_num . ' ' . $group_size . PHP_EOL;
    if ($current_spring === '?') {
        //echo 'Current group: ' . substr($springs, $spring_pos, $group_size) . ' last spring: ' . $springs[$spring_pos + $group_size] . PHP_EOL;
        if (! str_contains(substr($springs, $spring_pos, $group_size), '.') && $springs[$spring_pos + $group_size] !== '#') {
            $count = count_patterns($spring_pos + $group_size + 1, $group_num + 1, $springs, $groups) + count_patterns($spring_pos + 1, $group_num, $springs, $groups);
        } else {
            $count = count_patterns($spring_pos + 1, $group_num, $springs, $groups);
        }
    } elseif ($current_spring === '#') {
        if (! str_contains(substr($springs, $spring_pos, $group_size), '.') && $springs[$spring_pos + $group_size] !== '#') {
            $count = count_patterns($spring_pos + $group_size + 1, $group_num + 1, $springs, $groups);
        } else {
            $count = 0;
        }
    } elseif ($current_spring === '.') {
        $count = count_patterns($spring_pos + 1, $group_num, $springs, $groups);
    }

    $cache["$spring_pos,$group_num"] = $count;
    //echo $spring_pos . ' ' . $group_num . ' ' . $group_size . ' ' . $current_group . ' ' . $current_spring . ' ' . $last_spring . ' ' . $count . PHP_EOL;
    return $count;
}
