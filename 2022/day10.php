<?php

$lines = explode( PHP_EOL, file_get_contents( 'day10.in' ));


$cycle = 0;

while true {
    
}
foreach ($lines as $k=>$line) {
    $cmds = explode(' ', $line);
    if (count($cmds)>1) {
        list($cmd, $num) = $cmds;
    } else {
        $cmd = $cmds[0];
        $num = null;
    }

    echo $cmd,$num,PHP_EOL;
}
