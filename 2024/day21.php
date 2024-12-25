<?php

require 'common.php';

$lines = get_input(21, false);

$codes = [];
foreach ($lines as $y => $line) {
    $codes[] = $line;
}
$keypad_moves = [
    'A' => [
        '0' => '<',
        '1' => '^<<',
        '2' => '^<',
        '3' => '^',
        '4' => '^^<<',
        '5' => '^^<',
        '6' => '^^',
        '7' => '^^^<<',
        '8' => '^^^<',
        '9' => '^^^',
    ],
    '0' => [
        'A' => '>',
        '0' => '',
        '1' => '^<',
        '2' => '^',
        '3' => '^>',
        '4' => '^^<',
        '5' => '^^',
        '6' => '^^>',
        '7' => '^^^<',
        '8' => '^^^',
        '9' => '^^^>',
    ],
    '1' => [
        'A' => '>>v',
        '0' => '>v',
        '1' => '',
        '2' => '>',
        '3' => '>>',
        '4' => '^',
        '5' => '^>',
        '6' => '>>^',
        '7' => '^^',
        '8' => '^^>',
        '9' => '^^>>',
    ],
    '2' => [
        'A' => '>v',
        '0' => 'v',
        '1' => '<',
        '2' => '',
        '3' => '>',
        '4' => '^<',
        '5' => '^',
        '6' => '^>',
        '7' => '^^<',
        '8' => '^^',
        '9' => '^^>',
    ],
    '3' => [
        'A' => 'v',
        '0' => 'v<',
        '1' => '<<',
        '2' => '<',
        '3' => '',
        '4' => '^<<',
        '5' => '^<',
        '6' => '^',
        '7' => '^^<<',
        '8' => '^^<',
        '9' => '^^',
    ],
    '4' => [
        'A' => '>>vv',
        '0' => '>vv',
        '1' => 'v',
        '2' => '>v',
        '3' => '>>v',
        '4' => '',
        '5' => '>',
        '6' => '>>',
        '7' => '^',
        '8' => '^>',
        '9' => '^>>',
    ],
    '5' => [
        'A' => '>vv',
        '0' => 'vv',
        '1' => 'v<',
        '2' => 'v',
        '3' => 'v>',
        '4' => '<',
        '5' => '',
        '6' => '>',
        '7' => '^<',
        '8' => '^',
        '9' => '^>',
    ],
    '6' => [
        'A' => 'vv',
        '0' => 'vv<',
        '1' => 'v<<',
        '2' => 'v<',
        '3' => 'v',
        '4' => '<<',
        '5' => '<',
        '6' => '',
        '7' => '^<<',
        '8' => '^<',
        '9' => '^',
    ],
    '7' => [
        'A' => '>>vvv',
        '0' => '>vvv',
        '1' => 'vv',
        '2' => 'vv>',
        '3' => 'vv>>',
        '4' => 'v',
        '5' => 'v>',
        '6' => 'v>>',
        '7' => '',
        '8' => '>',
        '9' => '>>',
    ],
    '8' => [
        'A' => '>vvv',
        '0' => 'vvv',
        '1' => 'vv<',
        '2' => 'vv',
        '3' => 'vv>',
        '4' => 'v<',
        '5' => 'v',
        '6' => 'v>',
        '7' => '<',
        '8' => '',
        '9' => '>',
    ],
    '9' => [
        'A' => 'vvv',
        '0' => 'vvv<',
        '1' => 'vv<<',
        '2' => 'vv<',
        '3' => 'vv',
        '4' => 'v<<',
        '5' => 'v<',
        '6' => 'v',
        '7' => '<<',
        '8' => '<',
        '9' => '',
    ],
];

$dir_moves = [
    'A' => [
        'A' => '',
        '^' => '<',
        '<' => 'v<<',
        'v' => 'v<',
        '>' => 'v',
    ],
    '^' => [
        'A' => '>',
        '^' => '',
        '<' => 'v<',
        'v' => 'v',
        '>' => 'v>',
    ],
    '<' => [
        'A' => '>>^',
        '^' => '>^',
        '<' => '',
        'v' => '>',
        '>' => '>>',
    ],
    'v' => [
        'A' => '>^',
        '^' => '^',
        '<' => '<',
        'v' => '',
        '>' => '>',
    ],
    '>' => [
        'A' => '^',
        '^' => '<^',
        '<' => '<<',
        'v' => '<',
        '>' => '',
    ],
];

$sum = 0;
foreach ($codes as $code) {
    $current_pos = 'A';
    $moves = '';
    for ($i = 0; $i < strlen($code); $i++) {
        //l("Starting at: $current_pos - going to: {$code[$i]}");
        $moves .= $keypad_moves[$current_pos][$code[$i]] . 'A';
        $current_pos = $code[$i];
    }
    //l("Bot 1 moves: $moves");

    $bot2_moves = '';
    for ($i = 0; $i < strlen($moves); $i++) {
        //l("Starting at: $current_pos - going to: {$moves[$i]} = {$dir_moves[$current_pos][$moves[$i]]}");
        $bot2_moves .= $dir_moves[$current_pos][$moves[$i]] . 'A';
        $current_pos = $moves[$i];
    }
    //l("Bot 2 moves: $bot2_moves");

    $bot3_moves = '';
    for ($i = 0; $i < strlen($bot2_moves); $i++) {
        //l("Starting at: $current_pos - going to: {$bot2_moves[$i]} = {$dir_moves[$current_pos][$bot2_moves[$i]]}");
        $bot3_moves .= $dir_moves[$current_pos][$bot2_moves[$i]] . 'A';
        $current_pos = $bot2_moves[$i];
    }
    //l("Bot 3 moves: $bot3_moves");

    $product = strlen($bot3_moves) * substr($code, 0, 3);
    l(strlen($bot3_moves) . ' * ' . substr($code, 0, 3) . ' = ' . $product);
    $sum += $product;
}

l($sum);

//  <     A   ^  A >
// v<<A >>^A <A >A vA <^AA>A<vAAA>^A
// v<<A >>^A <A >A <A Av>A^Av<AAA>^A

// <v<A>>^AvA^A <vA  <AA>>^AAvA<^A>AAvA ^A<vA>^AA<A>A<v<A>A>^AAAvA<^A>A
// v<<A>>^AvA^A v<<A >>^AAv<A<A>>^AAvAA<^A >Av<A>^AA<A>Av<A<A>>^AAAvA<^A>A

// 258048 - too high
