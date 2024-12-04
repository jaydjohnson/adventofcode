<?php

$fabric = array_fill(1, 1000, array_fill(1, 1000, '.'));

$file = fopen('day4.txt', 'r');
$entries = [];
$logs = [];

while (($line = fgets($file)) !== false) {
	preg_match('/\[([\d-: ]+)\] (.*)/', $line, $matches);
	$timestamp = $matches[1];
	$date = strtotime($timestamp);
	$action = $matches[2];
	$entries[$date] = $action;

	// echo date("Y-m-d", $date) . " - " . $action . PHP_EOL;
	//echo "$line" . PHP_EOL;
	//print_r($fabric);
	// echo $line . ($found2 ? ' 2s' : '') .  ($found3 ? ' 3s' : '') . PHP_EOL;
}

ksort($entries);
print_r($entries);

foreach ($entries as $time => $description) {
	$hour = date("H", $time);
	$day = $hour == '23' ? date("m-d", strtotime("+1 day", $time)) :date("m-d", $time);
	
	preg_match('/(\w+) #?(\d+)?/', $description, $matches);
	$action = $matches[1];
	$guard = isset($matches[2]) ? $matches[2] : null;

	//echo "$day $hour - $guard - $action" . PHP_EOL;
	switch ($action) {
		case "Guard":
			// Set up new Guard
			$logs[$day]['guard'] = $guard;
			$logs[$day]['timeline'] = array_fill(0, 60, '.');
			$asleep = false;
			break;
		case "falls":
			$fell_asleep_at = (int) date("i", $time);
			$asleep = true;
			break;
		case "wakes":
			$wakes_at = (int) date("i", $time);
			$asleep = false;
			for ($x=$fell_asleep_at; $x<$wakes_at; $x++) {
				$logs[$day]['timeline'][$x] = "X";
			}
			break;
	}
	//echo $action . " " . $guard . " " . date("i", $time) . PHP_EOL;

	//showLogs($logs);
}

showLogs($logs);
calculateEachMinute($logs);
// getMinute($logs, $sleepyGuard);

fclose($file);

function getMinute($logs, $sleepyGuard) {
	$minutes = array_fill(0, 60, 0);
	foreach ($logs as $log) {
		if ($log['guard'] == $sleepyGuard) {
			foreach ($log['timeline'] as $k=>$wakeState) {
				$minutes[$k] += $wakeState == "X" ? 1 : 0;
			}
		}
	}

	echo "guard: " . $sleepyGuard . PHP_EOL;
	print_r($minutes);
}

function calculateEachMinute($logs) {
	$guards = [];
	foreach($logs as $log) {
		if (! isset($guards[$log['guard']])) {
			$guards[$log['guard']] = array_fill(0, 60, 0);
		}
		foreach($log['timeline'] as $k => $wakeState) {
			$guards[$log['guard']][$k] += $wakeState == "X" ? 1 : 0;
		}
	}

	return calculateMostSleptMinute($guards);
}

function calculateMostSleptMinute($guards) {
	$sleepyGuard = '';
	$highestTimesSlept = 0;
	$highestMinute = -1;
	foreach ($guards as $guard => $timeline) {
		foreach ($timeline as $minute=>$timesSlept) {
			if ($timesSlept > $highestTimesSlept) {
				$highestMinute = $minute;
				$highestTimesSlept = $timesSlept;
				$sleepyGuard = $guard;
			}
		}
	}

	echo $sleepyGuard . " " . $highestTimesSlept . " " . $highestMinute . PHP_EOL;
	// return $sleepyGuard;
}

function showLogs($logs) {
	foreach($logs as $day => $data) {
		echo "$day " . $data['guard'] . " ";
		foreach($data['timeline'] as $wakeState) {
			echo $wakeState;
		}
		echo PHP_EOL;
	}
}

