<?php

$lines = explode( PHP_EOL, file_get_contents( 'day6.in' ));

$dups = false;
foreach ($lines as $k=>$line) {
    for($i = 0; $i < strlen($line); $i++) {
        $dups = false;
        $test = substr($line, $i, 4);
        for($x = 0; $x < strlen($test); $x++) {
            preg_match_all('/'. $test[$x] . '/', $test, $matches);
            if (count($matches[0]) > 1) {
                $dups = true;
                break;
            }
        }

        if (! $dups ) {
            echo $i + 4 . PHP_EOL;
            break;
        }
    }

    for($i = 0; $i < strlen($line); $i++) {
        $dups = false;
        $test = substr($line, $i, 14);
        for($x = 0; $x < strlen($test); $x++) {
            preg_match_all('/'. $test[$x] . '/', $test, $matches);
            if (count($matches[0]) > 1) {
                $dups = true;
                break;
            }
        }

        if (! $dups ) {
            echo $i + 14;
            break;
        }
    }
}