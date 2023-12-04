<?php

include 'common.php';

$lines = get_input(4, false);
function part_one($lines) {
    $total_points = 0;
    foreach ($lines as $card => $line) {
        $numbers = explode( ": ", $line)[1];
        $winning_numbers =  explode(" ", explode(" | ", $numbers)[0]);
        $my_numbers = explode(" ", explode(" | ", $numbers)[1]);
        $winning_numbers = array_filter($winning_numbers);
        $my_numbers = array_filter($my_numbers);
        $num = 0;
        foreach($my_numbers as $my_num) {
            if (in_array($my_num, $winning_numbers)) {
                $num = $num === 0 ? 1 : $num * 2;
            }
        }
        $total_points += $num;
    }

    var_dump($total_points);
}

function part_two($lines) {
    $total_points = 0;
    $additional_scratches = array_fill(0, sizeof($lines), 0);
    foreach ($lines as $current_card => $line) {
        $numbers = explode( ": ", $line)[1];
        $winning_numbers =  explode(" ", explode(" | ", $numbers)[0]);
        $my_numbers = explode(" ", explode(" | ", $numbers)[1]);
        $winning_numbers = array_filter($winning_numbers);
        $my_numbers = array_filter($my_numbers);

        $num = 0;
        foreach($my_numbers as $my_num) {
            if (in_array($my_num, $winning_numbers)) {
                $num++;
            }
        }
        for($y = 0; $y < $num; $y++) {
            $additional_scratches[$current_card+$y+1]++;
        }
        for($x = 0; $x < $additional_scratches[$current_card]; $x++) {
            for($y = 0; $y < $num; $y++) {
                $additional_scratches[$current_card+$y+1]++;
            }
        }
    }

    var_dump(array_sum($additional_scratches)+sizeof($lines));
}
part_one($lines);
part_two($lines);
