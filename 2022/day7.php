<?php

$lines = explode( PHP_EOL, file_get_contents( 'day7.in' ));

$path_sizes = [];
$current_folder = '/';
$path = [];

$fs = new Files('/', null);
$fs->parent = null;

foreach ($lines as $k=>$line) {
    $output = explode( " ", $line);

    // var_dump($line);
    // var_dump($path);
    if ($output[1] == 'cd') {
        if ($output[2] == '..') {
            array_pop($path);
            // echo "changed to " . $output[2] . ' ' . implode('/', $path) . PHP_EOL;
        } else {
            array_push($path, $output[2]);
            // echo "changed to " . $output[2] . ' ' . implode('/', $path) . PHP_EOL;
        }
    } elseif ($output[1] == 'ls') {
        continue;
    } elseif ($output[0] == 'dir') {
        continue;
    } else {
        $size = $output[0];

        foreach ($path as $i => $folder) {
            $folder_path = implode('/', array_slice($path, 0, $i+1));
            if (!isset($path_sizes[$folder_path])) {
                $path_sizes[$folder_path] = 0;
            }
            $path_sizes[$folder_path] += $size;
        }
    }

}

$total_sum = 0;
foreach ($path_sizes as $path => $size) {
    if ($size <= 100000) {
        $total_sum += $size;
    }
}

echo $total_sum . PHP_EOL;

$needed_space = 30000000 - (70000000 - $path_sizes['/']);
$canidates = [];
foreach ($path_sizes as $path => $size) {
    if ($size >= $needed_space) {
        $canidates[$path] = $size;
    }
}
echo min($canidates) . PHP_EOL;
