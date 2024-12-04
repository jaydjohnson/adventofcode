<?php

$scoreboard = [3, 7];
$elf1Position = 0;
$elf2Position = 1;

// combine recipies
do {
	$newScore = $scoreboard[$elf1Position] + $scoreboard[$elf2Position];
	if ($newScore > 9) {
		$scoreString = (string)$newScore;
		$scoreboard[] = (int)$scoreString[0];
		$scoreboard[] = (int)$scoreString[1];
	} else {
		$scoreboard[] = $newScore;
	}

// move elfs
	/*
	if ($elf1Position + 1 + $scoreboard[$elf1Position] >= sizeof($scoreboard)) {
		$elf1Position += sizeof($scoreboard) - $elf1Position + 1 + $scoreboard[$elf1Position];
	} else {
		$elf1Position = 1 + $scoreboard[$elf1Position];
	}
	if ($elf2Position + 1 + $scoreboard[$elf2Position] >= sizeof($scoreboard)) {
		$elf2Position = sizeof($scoreboard) - $elf2Position + 1 + $scoreboard[$elf2Position];
	} else {
		$elf2Position = 1 + $scoreboard[$elf2Position];
	}*/
	$elf1Position  = ($elf1Position + $scoreboard[$elf1Position] + 1) % sizeof($scoreboard);
	$elf2Position  = ($elf2Position + $scoreboard[$elf2Position] + 1) % sizeof($scoreboard);
	//echo implode(", ", $scoreboard) . PHP_EOL;
} while (sizeof($scoreboard) < 939611);
echo implode('', array_splice($scoreboard, 939601, 10));
