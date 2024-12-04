<?php
include __DIR__ . '/utils.php';

gc_disable();
ini_set('memory_limit', '1024M');

$file = fopen('day15.txt', 'r');
$gameCave = [];
while (($line = fgets($file)) !== false) {
    $gameCave[] = trim($line);
}
$caves = [
    ['rounds' => 80, 'hp' => 2756, 'board' => $gameCave],
    // ['rounds' => 34, 'hp' => 301, 'board' => ['#######', '#.E..G#', '#.#####', '#G#####', '#######']],
    // ['rounds' => 34, 'hp' => 295, 'board' => ['#####', '###G#', '###.#', '#.E.#', '#G###', '#####']],
    // ['rounds' => 67, 'hp' => 200, 'board' => ['####', '##E#', '#GG#', '####']],
    // ['rounds' => 71, 'hp' => 197, 'board' => ['#####', '#GG##', '#.###', '#..E#', '#.#G#', '#.E##', '#####']],
    // ['rounds' => 37, 'hp' => 982, 'board' => ['#######', '#G..#E#', '#E#E.E#', '#G.##.#', '#...#E#', '#...E.#', '#######']],
    // ['rounds' => 46, 'hp' => 859, 'board' => ['#######', '#E..EG#', '#.#G.E#', '#E.##E#', '#G..#.#', '#..E#.#', '#######']],
    // ['rounds' => 35, 'hp' => 793, 'board' => ['#######', '#E.G#.#', '#.#G..#', '#G.#.G#', '#G..#.#', '#...E.#', '#######']],
    // ['rounds' => 54, 'hp' => 536, 'board' => ['#######', '#.E...#', '#.#..G#', '#.###.#', '#E#G#G#', '#...#G#', '#######']],
    // ['rounds' => 20, 'hp' => 937, 'board' => ['#########', '#G......#', '#.E.#...#', '#..##..G#', '#...##..#', '#...#...#', '#.G...G.#', '#.....G.#', '#########']],
];

foreach ($caves as $caveData) {
    $cave    = [];
    $units   = [];
    $goblins = [];
    $elfs    = [];
    $y       = 0;
    foreach ($caveData['board'] as $line) {
        for ($i = 0; $i < strlen($line); $i++) {
            if ($line[$i] == "G") {
                $units[] = new Unit($i, $y, "Goblin", 3);
            }
            if ($line[$i] == "E") {
                $units[] = new Unit($i, $y, "Elf", 20);
            }
        }
        $cave[] = trim($line);
        $y++;
    }
    
    drawCave($cave, 0, $units);
    //$units[4]->move($units, $cave);
    $fullRounds = 0;
    while (checkUnits($units)) {
        usort($units, "readingOrder");
        
        $numUnits = sizeof($units);
        for ($k = 0; $k < $numUnits; $k++) {
            if (!isset($units[$k])) {
                continue;
            }
            $unit = $units[$k];
            if ($unit->hitpoints <= 0) {
                break;
            }
            if (! $unit->attack($units, $cave)) {
                if (!$unit->move($units, $cave)) {
                    break 2;
                };
                $unit->attack($units, $cave);
            }
            if (checkUnits($units) == false && $k+1 < sizeof($units)) {
                $fullRounds--;
                break 1;
            }
        }
        $fullRounds++;
        //drawCave($cave, $fullRounds, $units);
    }
    $sumHealth = 0;
    foreach ($units as $unit) {
        $sumHealth += $unit->hitpoints;
    }
    drawCave($cave, $fullRounds, $units);
    // if ($caveData['rounds'] == $fullRounds && $caveData['hp'] == $sumHealth) {
         echo "Success!!  Game ended in $fullRounds Rounds, with $sumHealth left.  score: " . ($fullRounds * $sumHealth) . PHP_EOL;
    // } else {
    //     echo "FAILED!!  Game ended in $fullRounds/{$caveData['rounds']} Rounds, with $sumHealth/{$caveData['hp']} left.  score: " . ($fullRounds * $sumHealth) . PHP_EOL;
    // }
}

function drawCave($cave, $round, $units)
{
    //system('clear');
    //sleep(1);
    //echo chr(27).chr(91).'H'.chr(27).chr(91).'J';
    echo "-------- $round ----------" . PHP_EOL;
    foreach ($cave as $y => $line) {
        echo $line . "    ";
        drawHealth($units, $y);
        echo PHP_EOL;
    }
    echo PHP_EOL . PHP_EOL;
}

function drawHealth($units, $y)
{
    $lineUnit = [];
    foreach ($units as $unit) {
        if ($y == $unit->y) {
            $lineUnit[] = $unit;
        }
    }
    usort($lineUnit, function ($a, $b) {
        return $a->x < $b->x ? -1 : 1;
    });
    foreach ($lineUnit as $unit) {
        echo ($unit->team == "Goblin" ? "G" : "E") . "(" . $unit->hitpoints . "), ";
    }
}

function checkUnits($units)
{
    $g = false;
    $e = false;
    foreach ($units as $unit) {
        if ($unit->team == "Goblin") {
            $g = true;
        } elseif ($unit->team = "Elf") {
            $e = true;
        }
    }

    return $e && $g;
}

class Unit
{
    public $team;
    public $x;
    public $y;
    public $hitpoints   = 200;
    public $attackPower = 3;

    public function __construct($x, $y, $team, $ap)
    {
        $this->x    = $x;
        $this->y    = $y;
        $this->team = $team;
        $this->attackPower = $ap;
    }

    public function attack(&$units, &$cave)
    {

        $enemies = $this->getEnemies($units);
        $enemySquares = $this->getAdjacentSquares($this->x, $this->y, $cave, $this->team == "Goblin" ? "E" : "G");
        foreach ($enemySquares as $enemySquare) {
            $targets[] = $this->existInObject($enemySquare['x'], $enemySquare['y'], $enemies);
        }

        if (empty($targets)) {
            return false;
        }

        foreach ($targets as $target) {
            if (!isset($attackTarget) || $target->hitpoints < $attackTarget->hitpoints) {
                $attackTarget = $target;
            }
        }

        foreach ($units as $k => $unit) {
            if ($unit == $attackTarget) {
                $unit->hitpoints -= $this->attackPower;
                if ($unit->hitpoints <= 0) {
                    $cave[$unit->y][$unit->x] = '.';
                    unset($units[$k]);
                }
                return true;
            }
        }

        return false;
    }

    public function move($units, &$cave)
    {
        $enemies                 = $this->getEnemies($units);
        if (empty($enemies)) {
            return false;
        }
        $nearestEnemiesSquares   = $this->getInRangeEnemies($enemies, $cave);
        if (!empty($nearestEnemiesSquares)) {
            $reachableEnemiesSquares = $this->getReachableEnemies($nearestEnemiesSquares, $cave);
            if (!empty($reachableEnemiesSquares)) {
                $closestTarget           = $this->findClosestTarget($reachableEnemiesSquares);
                $moveToSquare            = $this->findShortestPath($closestTarget, $cave);
                $cave[$this->y][$this->x] = ".";
                $this->x = $moveToSquare['x'];
                $this->y = $moveToSquare['y'];
                $cave[$this->y][$this->x] = $this->team == "Goblin" ? "G" : "E";
            }
        } else {
            // return false;
        }

        return true;
    }

    public function getEnemies($units)
    {
        $enemy   = $this->team == "Goblin" ? "Elf" : "Goblin";
        $enemies = [];
        foreach ($units as $player) {
            if ($player->team == $enemy) {
                $enemies[] = $player;
            }
        }
        return $enemies;
    }

    public function isEnemy($unit)
    {
        return $this->team == $unit->team;
    }

    public function getInRangeEnemies($enemies, $cave)
    {
        $squares = [];
        foreach ($enemies as $enemy) {
            $squares = array_merge($squares, $this->getAdjacentSquares($enemy->x, $enemy->y, $cave, '.'));
        }

        return $squares;
    }

    public function getAdjacentSquares($x, $y, $cave, $type)
    {
        $openSquares = [];

        if ($cave[$y - 1][$x] == $type) {
            $openSquares[] = ['x' => $x, 'y' => $y - 1];
        }
        if ($cave[$y][$x - 1] == $type) {
            $openSquares[] = ['x' => $x - 1, 'y' => $y];
        }
        if ($cave[$y][$x + 1] == $type) {
            $openSquares[] = ['x' => $x + 1, 'y' => $y];
        }
        if ($cave[$y + 1][$x] == $type) {
            $openSquares[] = ['x' => $x, 'y' => $y + 1];
        }

        return $openSquares;
    }

    public function getReachableEnemies($nearestEnemiesSquares, $cave)
    {
        $reachableSquares = [];
        $distance         = 0;
        $currentSquare[]    = ['x' => $this->x, 'y' => $this->y];

        while (!empty($currentSquare)) {
            $newDest = [];

            foreach ($currentSquare as $square) {
                if (in_array($square, $nearestEnemiesSquares)) {
                    $reachableSquares[] = $square + ['distance' => $distance];
                }

                // Find Path
                $newDest = array_merge($newDest, $this->getAdjacentSquares($square['x'], $square['y'], $cave, "."));

                // Mark Read squares
                $cave[$square['y']][$square['x']] = 'x';
            }

            $currentSquare = array_unique($newDest, SORT_REGULAR);
            $distance++;
        }

        return $reachableSquares;
    }

    public function findClosestTarget($reachableSquares)
    {
        $closest = [];
        usort($reachableSquares, "readingOrderArray");
        foreach ($reachableSquares as $square) {
            if ($closest == [] || $square['distance'] < $closest['distance']) {
                $closest = $square;
            }
        }
        return $closest;
    }

    public function findShortestPath($target, $cave)
    {
        $distance = 0;
        $dist = [];
        $dest = [['x' => $target['x'], 'y' => $target['y']]];
        $cave[$target['y']][$target['x']] = 0;

        while (!empty($dest)) {
            $distance++;
            $new = [];

            foreach ($dest as $d) {
                $new = array_merge($new, $this->getAdjacentSquares($d['x'], $d['y'], $cave, '.'));
                $dist[] = array_merge($d, ['distance' => $distance]);
                $cave[$d['y']][$d['x']] = $distance;
            }

            $dest = array_unique($new, SORT_REGULAR);
        }

        foreach ([
            ['y' => $this->y - 1, 'x' => $this->x],
            ['y' => $this->y, 'x' => $this->x - 1],
            ['y' => $this->y, 'x' => $this->x + 1],
            ['y' => $this->y + 1, 'x' => $this->x]
        ] as $c) {
            $d = $this->existInArray($c['x'], $c['y'], $dist);
            if ($d === false) {
                continue;
            }
            if (!isset($path) || $d['distance'] < $path['distance']) {
                $path = $d;
            }
        }
        return $path;
    }

    public function existInArray($x, $y, $arr)
    {
        foreach ($arr as $a) {
            if ($a['x'] == $x && $a['y'] == $y) {
                return $a;
            }
        }
        return false;
    }

    public function existInObject($x, $y, $units)
    {
        foreach ($units as $unit) {
            if ($unit->x == $x && $unit->y == $y) {
                return $unit;
            }
        }
        return false;
    }
}

class Goblin extends Unit
{
    public function __construct($x, $y)
    {
        parent::__construct($x, $y, "Goblin");
    }
}

class Elf extends Unit
{
    public function __construct($x, $y)
    {
        parent::__construct($x, $y, "Elf");
    }
}

function readingOrder($a, $b)
{
    if ($a->y == $b->y) {
        return $a->x < $b->x ? -1 : 1;
    }
    return $a->y < $b->y ? -1 : 1;
}

function readingOrderArray($a, $b)
{
    if ($a['y'] == $b['y']) {
        return $a['x'] < $b['x'] ? -1 : 1;
    }
    return $a['y'] < $b['y'] ? -1 : 1;
}

//Callback to sort players by grid position
function sortby_grid_position($a, $b)
{
    $vert_order = $a[1] <=> $b[1];
    if ($vert_order == 0) {
        return ($a[0] <=> $b[0]);
    }
    return $vert_order;
}

//Callback to sort players by health
function sortby_health($a, $b)
{
    $health_order = $a[3] <=> $b[3];
    if ($health_order == 0) {
        $vert_order = $a[1] <=> $b[1];
        if ($vert_order == 0) {
            return ($a[0] <=> $b[0]);
        }
        return $vert_order;
    }
    return $health_order;
}
