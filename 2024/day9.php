<?php

include 'common.php';

$lines = get_input(9, true);

$map = '';
foreach ($lines as $y => $line) {
    $map = array_map('intval', str_split($line));
}

v($map);

$str = '';
$id = 0;
$free = [];
for ($i = 0; $i < count($map); $i++) {
    if ($i % 2 === 0) {
        $str .= str_repeat($id, $map[$i]);
        $id++;
    } else {
        //$free = array_merge($free, range(strlen($str), $map[$i] + strlen($str) - 1));
        $str .= str_repeat('.', $map[$i]);
    }
}

v($free);
l($str);

// while (! empty($free)) {
//     $espot = strpos($str, '.');
//     $nspot = strrpos($str, '9');
// }
