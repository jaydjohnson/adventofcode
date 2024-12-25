<?php

require 'common.php';

$groups = get_input(19, true, true);

$patterns = explode(', ', $groups[0]);
$designs = explode(PHP_EOL, $groups[1]);

$found = 0;
foreach ($designs as $y => $design) {
    l("checking design $design");
    if (find($design, 0, 0, '')) {
        l("Design Possible!!");
        $found++;
    }
}
v($found);
function find($design, $index, $size, $str) {
    global $patterns;
    l("Current String: $str");
    if ($str === $design) {
        return true;
    }
    l("current substr: " . substr($design, $index, $size));
    if (in_array(substr($design, $index, $size), $patterns)) {
        $str .= substr($design, $index, $size);
        return find($design, $index+1, 1, $str);
    } else {
        if ($index + $size + 1 >= strlen($design)) {
            l("not found");
            return false;
        }
        return find($design, $index, $size+1, $str);
    }
}
