<?php

$lines = explode( PHP_EOL, file_get_contents( 'day3.in' ));

$priority_sum = 0;
$group_priority_sum = 0;
$group = 0;
$groups = [];
$all_gorup_containers = '';

foreach ($lines as $k=>$line) {
    $container1 = substr($line, 0, strlen($line) / 2);
    $container2 = substr($line, strlen($line) / 2);
    for ($i = 0; $i < strlen($container2); $i++) {
        if (strstr($container1, $container2[$i])) {
            $ltr =  $container2[$i];
            $priority_sum += alpha2num($ltr);
            break;
        }
    }

    $groups[$group][] = $line;
    $all_gorup_containers .= $line;
    if (($k+1) % 3 === 0) {
        for ($i = 0; $i < strlen($all_gorup_containers); $i++) {
            if (strstr($groups[$group][0], $all_gorup_containers[$i]) &&
                strstr($groups[$group][1], $all_gorup_containers[$i]) &&
                strstr($groups[$group][2], $all_gorup_containers[$i])) {
                echo 'found letter: ' . $all_gorup_containers[$i] . PHP_EOL;
                $group_priority_sum += alpha2num($all_gorup_containers[$i]);
                break;
            }
        }
        $all_gorup_containers = '';
        $group++;

    }
    
}

echo $priority_sum . PHP_EOL;
echo $group_priority_sum . PHP_EOL;

function alpha2num($a) {
    $lc = array_merge( range('a', 'z'), range('A', 'Z'));
    return array_search($a, $lc)+1;
}