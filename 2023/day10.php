<?php

require 'common.php';

$lines = get_input(10, false);

$directions = ['N' => [0, -1], 'S' => [0, 1], 'W' => [-1, 0], 'E' => [1, 0]];
$start = [];
foreach ($lines as $y => $line) {
    $map[] = str_split($line);
    if (str_contains($line, 'S')) {
        $start = [strpos($line, 'S'), $y];
    }
}
$dir = get_first_direction($map, $start);
//$current_pos = [$start[0]+$directions[$dir][0],$start[1]+$directions[$dir][1]];
$current_pos = [$start[0],$start[1]];
$count = 0;
while (true) {
    // go dir
    $current_pos = [$current_pos[0]+$directions[$dir][0],$current_pos[1]+$directions[$dir][1]];
    $segment = $map[$current_pos[1]][$current_pos[0]];
    //echo 'Current on ' . $current_pos[0] . ', ' . $current_pos[1] . ' going ' . $dir . "({$map[$current_pos[1]][$current_pos[0]]})($segment)" . PHP_EOL;
    $path[] = $current_pos;

    //$map[$current_pos[1]][$current_pos[0]] = $segment !== '-' ? 'X' : $segment;
    $dir = get_next_direction($segment, $dir);
    //echo 'next direction: ' . $dir . PHP_EOL;
    if ($segment === 'S') {
        break;
    }
    $count++;
}

d(count($path)/2);

$map[$start[1]][$start[0]] = get_s_shape($map, $start);
// clear map
foreach($map as $y => $row) {
    foreach($row as $x => $column) {
        if (! in_array([$x, $y], $path)) {
            $map[$y][$x] = '.';
        }
    }
}


$total = 0;
foreach($map as $y => $row) {

    if ($y !== 5) {
        //continue;
    }
    $within = false;
    $up = false;
    $down = false;
    foreach($row as $x => $char) {
        
        if ($char === '|') {
            $within = ! $within;
        } elseif ($char === '-') {
            assert($up || $down);
        } elseif ($char === 'L') {
            $up = true;
            $within = ! $within;
        } elseif ($char === 'F') {
            $down = true;
            $within = ! $within;
        } elseif ($char === 'J') {
            if ($up) {
                $within = ! $within;
            }
            //$within = ! $within && $up === true;
            $up = false;
            $down = false;
        } elseif ($char === '7') {
            if ($down) {
                $within = ! $within;
            }
            $up = false;
            $down = false;
        } elseif ($char === '.') {
            $total += $within ? 1 : 0;
        } else {
            throw new Exception('unexpected char');
        }
        // echo $char . ' ' . ($within ? 'W' : '.') . ' ' . ($within && $char === '.' ? '1' : '0') . PHP_EOL;
    }
    // echo 'total  ' . $total . PHP_EOL;
}

d($total);


function get_next_direction($current_seg, $dir) {
    switch($dir) {
        case 'N': {
            if ($current_seg === '|') {
                return 'N';
            }
            if ($current_seg === '7') {
                return 'W';
            }
            if ($current_seg === 'F') {
                return 'E';
            }
        } 
        case 'E': {
            if ($current_seg === '7') {
                return 'S';
            }
            if ($current_seg === 'J') {
                return 'N';
            }
            if ($current_seg === '-') {
                return 'E';
            }
        }
        case 'S': {
            if ($current_seg === '|') {
                return 'S';
            }
            if ($current_seg === 'J') {
                return 'W';
            }
            if ($current_seg === 'L') {
                return 'E';
            }
        }
        case 'W': {
            if ($current_seg === '-') {
                return 'W';
            }
            if ($current_seg === 'L') {
                return 'N';
            }
            if ($current_seg === 'F') {
                return 'S';
            }
        }
    }
}

function get_first_direction($map, $start) {
    //               U R D L
    $adjacents = [[-1,0], [0,1],[1,0],[0,-1]];
    foreach ($adjacents as $k => $adj) {
        $checkx = $start[0]+$adj[1];
        $checky = $start[1]+$adj[0];
        if (inbounds($checkx, $checky, count($map[0]), count($map))) {
            // echo 'inbounds '. $k . ' x' . $checkx . ' y' . $checky . PHP_EOL;
            // d($map[$checky][$checkx]);
            if ($k === 0) {
                
                if ($map[$checky][$checkx] === '7') {
                    return 'N';
                }
                if ($map[$checky][$checkx] === '|') {
                    return 'N';
                }
                if ($map[$checky][$checkx] === 'F') {
                    return 'N';
                }
            }
            if ($k === 1) {
                
                if ($map[$checky][$checkx] === '-') {
                    return 'E';
                }
                if ($map[$checky][$checkx] === 'J') {
                    return 'E';
                }
                if ($map[$checky][$checkx] === '7') {
                    return 'E';
                }
            }
            if ($k === 2) {
                
                if ($map[$checky][$checkx] === '|') {
                    return 'S';
                }
                if ($map[$checky][$checkx] === 'J') {
                    return 'S';
                }
                if ($map[$checky][$checkx] === 'L') {
                    return 'S';
                }
            }
            if ($k === 1) {
                
                if ($map[$checky][$checkx] === '-') {
                    return 'W';
                }
                if ($map[$checky][$checkx] === 'F') {
                    return 'W';
                }
                if ($map[$checky][$checkx] === 'L') {
                    return 'W';
                }
            }
        } else {
            //echo 'not inbounds: ' . $checkx . ' ' . $checky . PHP_EOL;
        }
    }
}

function get_s_shape($map, $start) {
    //               U R D L
    $adjacents = [[-1,0], [0,1],[1,0],[0,-1]];
    $s = ['|', '-', 'F', '7', 'J', 'L'];
    foreach ($adjacents as $k => $adj) {
        $checkx = $start[0]+$adj[1];
        $checky = $start[1]+$adj[0];
        if (inbounds($checkx, $checky, count($map[0]), count($map))) {
            // echo 'inbounds '. $k . ' x' . $checkx . ' y' . $checky . PHP_EOL;
            // d($map[$checky][$checkx]);
            if ($k === 0) {
                if (in_array($map[$checky][$checkx], ['|','F','7'])) {
                    $s = array_intersect($s, ['|','J','L']);
                }
            }
            if ($k === 1) {
                if (in_array($map[$checky][$checkx], ['-','J','7'])) {
                    $s = array_intersect($s, ['-','L','F']);
                }
            }
            if ($k === 2) {
                if (in_array($map[$checky][$checkx], ['|','J','L'])) {
                    $s = array_intersect($s, ['|','7','F']);
                }
            }
            if ($k === 3) {
                if (in_array($map[$checky][$checkx], ['-','F','L'])) {
                    $s = array_intersect($s, ['-','J','7']);
                }
            }
        } else {
            //echo 'not inbounds: ' . $checkx . ' ' . $checky . PHP_EOL;
        }
    }
    return array_values($s)[0];
}