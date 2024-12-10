<?php

include 'common.php';

$lines = get_input(9, true);

$map = '';
foreach ($lines as $y => $line) {
    $map = $line;
}

$str = '';
$id = 0;
$free = [];
for($i = 0; $i < strlen($map); $i++) {
    if ($i % 2 === 0) {
        $str .= str_repeat($id, $map[$i]);
        $id++;
    } else {
        $free = array_merge($free, range(strlen($str), $map[$i] + strlen($str) - 1));
        $str .= str_repeat('.', $map[$i]);
    }
}

        vd($free);
echo $str . PHP_EOL;

while(! empty($free)) {
    $espot = strpos($str, '.');
    $nspot = strrpos($str, '9');
    str_replace()
}