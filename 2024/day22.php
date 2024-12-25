<?php

require 'common.php';

$lines = get_input(22, false);

$secret_numbers = [];
$changes = [];
foreach ($lines as $y => $line) {
    $secret_numbers[] = (int) $line;
}
d($secret_numbers);
$sum = 0;
foreach ($secret_numbers as $j => $num) {
    $ori = $num;
    $seq = [];
    $prev = abs($num % 10);
    $current_sequences = [];
    for ($i = 0; $i < 2000; $i++) {
        $num = calc_next($num);
        $last_digit = abs($num % 10);
        // l("$num: " . $last_digit . ' ' . $last_digit - $prev);
        
        $seq[] = $last_digit - $prev;
        if (count($seq) == 4) {
            $key = implode(',', $seq);
            if (isset($changes[$key])) {
                if (in_array($key, $current_sequences)) {
                    if ($last_digit > $changes[$key]) {
                        $changes[$key] += $last_digit;    
                    }
                } else {
                    // l("adding to array " . $key . ' - ' . $changes[$key] . ' + ' . $last_digit);
                    $changes[$key] += $last_digit;    
                    $current_sequences[] = $key;
                }
            } else {
                $changes[$key] = $last_digit;
                $current_sequences[] = $key;
            }
            array_shift($seq);
        }
        
        $prev = $last_digit;

    }
    $sum += $num;
    // l("$ori: $num");
}

// p1
l($sum);
// p2
arsort($changes);
d(array_shift($changes));

function calc_next($num) {
    // step 1
    $temp = $num * 64;
    $num = mix($num, $temp);
    $num = prune($num);
    // step 2
    $temp = (int) floor($num / 32);
    $num = mix($num, $temp);
    $num = prune($num);
    // step 3
    $temp = $num * 2048;
    $num = mix($num, $temp);
    $num = prune($num);
    return $num;
}

function mix($secret_num, $mixer) {
    return $secret_num ^ $mixer;
}

function prune($num) {
    return $num % 16777216;
}
