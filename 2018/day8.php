<?php


$file = fopen('day8.txt', 'r');
$nodes = [];
$license = [];
$meta = [];

while (($line = fgets($file)) !== false) {
	$license = preg_split("/ /", $line);
	get_node($meta, $license);
}

fclose($file);

print_r($meta);
print_r($license);

echo array_sum($meta);

function get_node(&$meta, &$license) {
	$children = array_shift($license);
	$meta_entries = array_shift($license);

	for ($child_count = 0; $child_count < $children; $child_count++) {
		get_node($meta, $license);
	}

	// B[0, 3] = 10 11 12 1 1 0 1 99 2 1 1 2
	// D[0, 1] = 99 2 1 1 2
	for ($x = 0; $x < $meta_entries; $x++) {
		$meta[] = array_shift($license);
	}
}

