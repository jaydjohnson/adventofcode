<?php

require 'common.php';

$lines = get_input(8, false);
$nodes = [];
foreach ($lines as $k => $line) {
	if ($k === 0) {
		$instructions = $line;
		continue;
	}
	if (empty($line)) {
		continue;
	}
	[
		$index,
		$next_elements,
	] = explode(' = ', $line);
	$next_elements = explode(', ', str_replace(['(', ')'], '', $next_elements));
	$nodes[$index] = $next_elements;
}

// $current_instruction = 0;
// $current_node = 'AAA';
// $steps = 0;
// do {
// if ($current_instruction >= strlen($instructions)) {
// $instructions .= $instructions;
// }
// d($instructions);
// $dir = $instructions[$current_instruction];
// $current_node = $nodes[$current_node][$dir === 'L' ? 0 : 1];
// $steps++;
// $current_instruction++;
// echo $steps . PHP_EOL;
// } while ($current_node !== 'ZZZ');
// echo "part 1: $steps" . PHP_EOL;
$anodes = array_filter(
	$nodes,
	function ($item, $k) {
		return str_ends_with($k, 'A');
	},
	ARRAY_FILTER_USE_BOTH
);
$cycles = [];
foreach ($anodes as $k => $anode) {
	$cycle         = [];
	$current_steps = $instructions;
	$step_count    = 0;
	$first_z       = '';

	while (true) {
		$current = $k;
		while ($step_count === 0 || ! str_ends_with($current, 'Z')) {
			$step_count++;
			$current       = $nodes[$current][$current_steps[0] === 'L' ? 0 : 1];
			$current_steps = substr($current_steps, 1).$current_steps[0];
			// d($current);
			// echo $step_count.' '.$current_steps.PHP_EOL;
		}
		echo "Found a Z in step $step_count".PHP_EOL;
		$cycle = $step_count;

		if ($first_z === '') {
			// echo 'Found first Z in '.$step_count.PHP_EOL;
			$first_z    = $current;
			$step_count = 0;
			break;
		} else if ($current === $first_z) {
			break;
		}
	}//end while

	$cycles[] = $cycle;
}//end foreach
d($cycles);

$lcm = array_pop($cycles);
foreach ($cycles as $cycle) {
	$lcm = ($lcm * $cycle / gmp_gcd($lcm, $cycle));
}
dd($lcm);
$steps = 0;
while (true) {
	if ($current_instruction >= strlen($instructions)) {
		$instructions .= $instructions;
	}
	$dir = $instructions[$current_instruction];

	$new_anodes = [];
	foreach ($anodes as $k => $anode) {
		$new_index = $nodes[$k][$dir === 'L' ? 0 : 1];
		$new_nodes = $nodes[$new_index];
		$new_anodes[$new_index] = $new_nodes;
	}
	$anodes = $new_anodes;
	$steps++;
	echo $steps.PHP_EOL;
	$continue = false;
	foreach ($anodes as $k => $anode) {
		if (!str_ends_with($k, 'Z')) {
			$continue = true;
		}
	}
	if (!$continue) {
		break;
	}
}//end while
