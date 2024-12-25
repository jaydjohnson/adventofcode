<?php

require 'common.php';

$lines = get_input(17, true);

$map = [];
$start = [];
$end = [];
$registers = [];
$program = [];
$output = [];
foreach ($lines as $y => $line) {
    if (strpos($line, 'Register') !== false) {
        $reg_name = explode(' ', explode(': ', $line)[0])[1];
        $reg_value = intval(explode(':', $line)[1]);
        $registers[$reg_name] = $reg_value;
    }
    if (strpos($line, 'Program') !== false) {
        $program = explode(',', explode(': ', $line)[1]);
    }
}

// $registers = [
//     'A' => 0,
//     'B' => 2024,
//     'C' => 43690,
// ];
// $program = [4, 0];

for ($i = 0; $i < count($program); $i++) {
    l("starting $i - {$program[$i]} with param: {$program[$i + 1]}");
    v($registers);
    if ($program[$i] == '3') {
        if ($registers['A'] != '0') {
            l("jumping to {$program[$i + 1]}");
            $i = $program[$i + 1] - 1;
            continue;
        }
    } else {
        l("running opcode: {$program[$i]} with param: {$program[$i + 1]}");
        eval_prog($program[$i], $program[$i + 1]);
    }
    $i++;
}
d($registers);
l(implode(',', $output));
function eval_prog($op, $param) {
    global $registers;
    global $output;
    switch ($op) { 
        case '0':
            $power = get_combo_op($param);
            $registers['A'] = (int) floor($registers['A'] / pow(2, $power));
            break;
        case '1':
            // Bitwise Operator
            $registers['B'] = (int) floor($registers['B'] ^ $param);
            break;
        case '2':
            $val = get_combo_op($param);
            $registers['B'] = (int) floor($val % 8);
            break;
        case '4':
            $registers['B'] = (int) floor($registers['B'] ^ $registers['C']);
            break;
        case '5':
            $num = get_combo_op($param);
            $val = (int) floor($num % 8);
            $output[] = $val;
            break;
        case '6':
            $power = get_combo_op($param);
            $registers['B'] = (int) floor($registers['A'] / pow(2, $power));
            break;
        case '7':
            $power = get_combo_op($param);
            $registers['C'] = (int) floor($registers['A'] / pow(2, $power));
            break;    
        default:
            break;
    }
}

function get_combo_op($param) {
    global $registers;
    switch ($param) {
        case '4':
            $value = $registers['A'];
            break;
        case '5':
            $value = $registers['B'];
            break;
        case '6':
            $value = $registers['C'];
            break;
        case '7':
            l("resrevred operand 7");
            exit();
            break;
        default:
            $value = (int) $param;
            break;
    }
    return $value;
}


// Wrong:
// 7,3,3,5,5,1,6,5,7
