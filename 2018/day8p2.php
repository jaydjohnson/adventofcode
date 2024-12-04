<?php


$file = fopen('day8.txt', 'r');
$nodes = [];
$license = [];
$meta = [];

while (($line = fgets($file)) !== false) {
	$license = preg_split("/ /", $line);
	$meta = get_node($meta, $license);
}

fclose($file);

print_r($meta);

echo sum_nodes($meta);

function get_node(&$meta, &$license, &$count = 0) {
	$children = array_shift($license);
	$meta_entries = array_shift($license);
	$current_node = [
		'children_count' => $children,
		'meta_entries' => $meta_entries,
	];
	$meta_key = chr($count+65);
	$count++;

	for ($child_count = 0; $child_count < $children; $child_count++) {
		$current_node['children'][] = get_node($meta, $license, $count);
	}

	// B[0, 3] = 10 11 12 1 1 0 1 99 2 1 1 2
	// D[0, 1] = 99 2 1 1 2
	for ($x = 0; $x < $meta_entries; $x++) {
		$current_node['meta'][] = array_shift($license);
	}
	return $current_node;
}

function sum_nodes($meta) {
	$current_sum = 0;

	if ($meta['children_count'] > 0) {
		foreach($meta['meta'] as $meta_value) {
			if (isset($meta['children'][$meta_value-1])) {
				$current_sum += sum_nodes($meta['children'][$meta_value-1]);
			}
		}
		return $current_sum;
	} else {
		return array_sum($meta['meta']);
	}
}