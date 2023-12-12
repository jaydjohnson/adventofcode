<?php

require 'common.php';

$lines = get_input(12, true);

$sum = 0;
foreach ($lines as $line) {
	[$springs, $groups] = explode(' ', $line);
	$groups = explode(',', $groups);
	$springs = $springs . '.';
	// echo $springs . PHP_EOL . implode(',', $groups) . PHP_EOL;
	echo " ----- new group ----- " . PHP_EOL;
	$s = count_patterns(0 ,0, $springs, $groups);
	echo "SUM for group: $s" . PHP_EOL;
	$sum += $s;
}

d($sum);

function count_patterns($spring_pos, $group_num, $springs, $groups) {
	// echo "Starting Count " . $spring_pos . ' ' . $group_num . PHP_EOL;
	if ($group_num >= count($groups)) {
		if ($spring_pos < strlen($springs) && str_contains(substr($springs, $spring_pos), '#')) {
			return 0; // not a solution
		}
		return 1;
	}

	if ($spring_pos >= strlen($springs)) {
		return -1; // ran out of springs
	}

	$count = 0;
	$group_size = $groups[$group_num];
	//$current_group = substr($springs, $spring_pos, $group_size);
	$current_spring = $springs[$spring_pos];
	//$last_spring = $springs[$spring_pos + $group_size];

	// echo 'Starting: ' . $spring_pos . ' ' . $group_num . ' ' . $group_size . PHP_EOL;
	if ($current_spring === '?') {
		//echo 'Current group: ' . substr($springs, $spring_pos, $group_size) . ' last spring: ' . $springs[$spring_pos + $group_size] . PHP_EOL;
		if (! str_contains(substr($springs, $spring_pos, $group_size), '.') && $springs[$spring_pos + $group_size] !== '#') {
			echo 'A' . PHP_EOL;
			$count = count_patterns($spring_pos + $group_size + 1, $group_num + 1, $springs, $groups) + count_patterns($spring_pos + 1, $group_num, $springs, $groups);
		} else {
			echo 'B' . PHP_EOL;
			$count = count_patterns($spring_pos + 1, $group_num, $springs, $groups);
		}
	} elseif ($current_spring === '#') {
		if (! str_contains(substr($springs, $spring_pos, $group_size), '.') && $springs[$spring_pos + $group_size] !== '#') {
			echo 'C' . PHP_EOL;
			$count = count_patterns($spring_pos + $group_size + 1, $group_num + 1, $springs, $groups);
		} else {
			echo 'D' . PHP_EOL;
			$count = 0;
		}
	} elseif ($current_spring === '.') {
		echo 'E' . PHP_EOL;
		$count = count_patterns($spring_pos + 1, $group_num, $springs, $groups);
	}
	//echo $spring_pos . ' ' . $group_num . ' ' . $group_size . ' ' . $current_group . ' ' . $current_spring . ' ' . $last_spring . ' ' . $count . PHP_EOL;
	return $count;
}