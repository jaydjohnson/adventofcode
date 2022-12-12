<?php

$lines = explode( PHP_EOL, file_get_contents( 'day11.in' ));

$monkeys = [];
for($i = 0; $i < count($lines); $i+=7) {
    preg_match('/(\d+)/', $lines[$i], $matches);
    $monkey_num = $matches[1];
    preg_match_all('/(\d+)/', $lines[$i+1], $matches);
    $monkey_items = $matches[0];
    preg_match_all('/old (.) (.+)/', $lines[$i+2], $matches);
    array_shift($matches);
    $monkey_op = [ $matches[0][0], $matches[1][0] ];
    preg_match('/(\d+)/', $lines[$i+3], $matches);
    $monkey_test = (int)$matches[1];
    preg_match('/(\d+)/', $lines[$i+4], $matches);
    $monkey_true = (int)$matches[1];
    preg_match('/(\d+)/', $lines[$i+5], $matches);
    $monkey_false = (int)$matches[1];

    $monkeys[$monkey_num] = [
        'items' => $monkey_items,
        'op' => $monkey_op,
        'test' => $monkey_test,
        'true' => $monkey_true,
        'false' => $monkey_false,
        'inspected' => 0,
    ];
}

$round = 1;
for($r=1; $r<=20; $r++) {
    foreach ($monkeys as $m => $monkey) {
        foreach ($monkeys[$m]['items'] as $item) {
            $worry = worry_calc($monkey['op'], $item);
            $worry = (int)($worry/3);
            $monkeys[$m]['inspected']++;
            // echo "Monkey $m inspecting $item with $worry mod {$monkey['test']}\n";
            if ($worry%$monkey['test'] == 0) {
                // echo "Monkey $m throwing $worry to " . $monkey['true'] . PHP_EOL;
                array_push($monkeys[$monkey['true']]['items'], $worry);
            } else {
                // echo "Monkey $m throwing $worry to " . $monkey['false'] . PHP_EOL;
                array_push($monkeys[$monkey['false']]['items'], $worry);
            }
        }
        $monkeys[$m]['items'] = [];
    }

    foreach ($monkeys as $m=>$monkey) {
        echo "Monkey $m: (" . $monkey['inspected'] . ") " . implode(", ", $monkey['items']) . PHP_EOL;
    }
}

function worry_calc($op, $item) {
    $operator = $op[0];
    $num = $op[1] == 'old' ? $item : $op[1];
    switch ($operator) {
        case '*': return $item * $num;
        case '+': return $item + $num;
    }

}