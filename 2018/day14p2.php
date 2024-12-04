<?php
ini_set('memory_limit', '2048M');

$scoreboard   = '37';
$elf1Position = 0;
$elf2Position = 1;

$input = '939601';
$recipies = 0;

// combine recipies
do {
    $newScore = $scoreboard[$elf1Position] + $scoreboard[$elf2Position];
    if ($newScore > 9) {
        $scoreString  = (string) $newScore;
        $scoreboard .= $scoreString[0];
        $scoreboard .= $scoreString[1];
    } else {
        $scoreboard .= $newScore;
    }

// move elfs
    $elf1Position = ($elf1Position + $scoreboard[$elf1Position] + 1) % strlen($scoreboard);
    $elf2Position = ($elf2Position + $scoreboard[$elf2Position] + 1) % strlen($scoreboard);
    //echo implode(", ", $scoreboard) . PHP_EOL;

    //$last = implode('', array_slice($scoreboard, -6, 6));
    $sbs  = strlen($scoreboard);
    $last = $scoreboard[$sbs - 6] . $scoreboard[$sbs - 5] . $scoreboard[$sbs - 4] . $scoreboard[$sbs - 3] . $scoreboard[$sbs - 2] . $scoreboard[$sbs - 1];
    //echo implode("", $scoreboard) . PHP_EOL;
    //$recipies++;

} while ($last != $input);

echo strlen($scoreboard) - 6;
