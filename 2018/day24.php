<?php

$armies = [];
$file = fopen("day24.txt", "r");
$team = '';
$immuneGroupNum = 0;
$infectionGroupNum = 0;
while (($line = fgets($file)) !== false) {
    if (trim($line) == "Immune System:") {
        $team = "immune";
        continue;
    } elseif (trim($line) == "Infection:") {
        $team = "infection";
        continue;
    } elseif (empty(trim($line))) {
        continue;
    }
    if ($team == 'immune') {
        $immuneGroupNum++;
    } else {
        $infectionGroupNum++;
    }
    preg_match("/(\d+) .+ (\d+) .+ \((.*)\) .+ (\d+) (\w+) .+ (\d+)/", $line, $matches);
    array_shift($matches);
    if (!empty($matches[2])) {
        $weaknesses = stripos($matches[2], ";") ? explode(";", $matches[2]) : [$matches[2]];
        foreach ($weaknesses as $weakness) {
            preg_match("/(weak|immune) to (.+)*/", $weakness, $types);
            array_shift($types);
            $matches[6][] = ['vulnerability' => $types[0], 'types' => explode(', ', $types[1])];
        }
    } else {
        $matches[6][] = ['vulnerability' => 'weak', 'types' => []];
    }
    $armies[] = new armies($team, $matches, $immuneGroupNum, $infectionGroupNum);
}

$originalArmies = cloneArmies($armies);
$previousSize = ['immune' => 1, 'infection' => 1];
$winner = '';
$boostAmount = 30;
boostImmune($armies, $boostAmount);
while ($winner !== 'immune') {
    $boostAmount++;
    $armies = cloneArmies($originalArmies);
    boostImmune($armies, $boostAmount);
    while (unitsLeft($armies, $previousSize)) {
        $previousSize = getArmySizes($armies);
        status($armies);
        $targets = getTargets($armies);
        attack($armies, $targets);
    }
    $winner = getWinner($armies);
}

function cloneArmies($original)
{
    $copies = [];
    foreach ($original as $obj) {
        $copies[] = clone $obj;
    }
    return $copies;
}

function getArmySizes($armies)
{
    $immuneUnits = $infectionUnits = 0;

    foreach ($armies as $army) {
        if ($army->team == 'immune') {
            $immuneUnits += $army->units;
        } else {
            $infectionUnits += $army->units;
        }
    }

    return ['immune' => $immuneUnits, 'infection' => $infectionUnits];
}

function getwinner($armies)
{
    $armyCount = getArmySizes($armies);

    return $armyCount['infection'] > 0 ? 'infection' : 'immune';
}

function unitsLeft($armies, $previousSize)
{
    $armyCount = getArmySizes($armies);

    if ($armyCount['immune'] == 0 || $armyCount['infection'] == 0) {
        echo "Winning Army Size: " . ($armyCount['immune'] + $armyCount['infection']) . PHP_EOL;
        return false;
    }

    if ($armyCount['immune'] == $previousSize['immune'] && $armyCount['infection'] == $previousSize['infection']) {
        // Stalemate
        echo " ((((((((((((((((((((((((((((((((((  STALEMATE ))))))))))))))))))))))))))))))))))" . PHP_EOL;
        return false;
    }

    return true;
}

function attack(&$armies, $targets)
{
    echo "Attacking:" . PHP_EOL;
    $attackOrder = $armies;
    uasort($attackOrder, "initiativeOrder");

    foreach ($attackOrder as $k => $group) {
        if ($group->units == 0) {
            continue;
        }
        if (!isset($targets[$k])) {
            continue;
        }
        $defendingGroup = $armies[$targets[$k]];
        $attackingGroup = $group;
        $killedUnits = $armies[$targets[$k]]->defend($attackingGroup);
        echo "$group->team group {$group->groupNumber} attacks defending $defendingGroup->team group {$defendingGroup->groupNumber} killing $killedUnits" . PHP_EOL;
    }
}

function getTargets($armies)
{
    $targetOrder = targetSelectOrder($armies);
    $attackingTeams = [];

    foreach ($targetOrder as $attackerKey => $group) {
        if ($group->units == 0) {
            continue;
        }
        //echo "$group->team:" . PHP_EOL;
        $enemyTeam = $group->team == 'immune' ? 'infection' : 'immune';
        $enemyGroups = getEnemyGroups($targetOrder, $group->team);
        $highestDamage = 0;
        foreach ($enemyGroups as $defenderKey => $enemyGroup) {
            if (in_array($defenderKey, $attackingTeams)) {
                continue;
            }
            if ($enemyGroup->units == 0) {
                continue;
            }
            $damage = $group->damageToGroup($enemyGroup);
            if ($damage > $highestDamage) {
                $highestDamage = $damage;
                $defendingGroup = $defenderKey;
            }
            //echo "$group->team group $group->groupNumber would deal defending group $enemyGroup->groupNumber $damage damage" . PHP_EOL;
        }
        if ($highestDamage > 0) {
            echo "$group->team group $group->groupNumber will attack defending group {$armies[$defendingGroup]->groupNumber} $highestDamage damage" . PHP_EOL;
            $attackingTeams[$attackerKey] = $defendingGroup;
        }
    }
    echo PHP_EOL;
    /*
    foreach (['immune' => getEnemyGroups($armies, 'infection'), 'infection' => getEnemyGroups($armies, 'immune')] as $team => $groups) {
        echo "$team:" . PHP_EOL;
        $enemyTeam = $team == 'immune' ? 'infection' : 'immune';
        $enemyGroups = attackOrder(getEnemyGroups($armies, $team));
        foreach ($groups as $k => $group) {
            $highestDamage = 0;
            foreach ($enemyGroups as $egk => $enemyGroup) {
                $damage = $group->damageToGroup($enemyGroup);
                if ($damage > $highestDamage) {
                    $highestDamage = $damage;
                    $attackGroup = $egk;
                }
                echo "$team group $group->groupNumber would deal defending group $enemyGroup->groupNumber $damage damage" . PHP_EOL;
            }
            if ($highestDamage > 0) {
                $attackingTeams[$k] = $attackGroup;
                unset($enemyGroups[$attackGroup]);
            }
        }
    }
    echo PHP_EOL;
*/
    return $attackingTeams;
}

function targetSelectOrder($targetOrder)
{
    uasort($targetOrder, "effectiveInitiativeOrder");
    echo "Target Select Order:" . PHP_EOL;
    foreach ($targetOrder as $group) {
        echo "$group->team $group->groupNumber: $group->units units $group->effectivePower." . PHP_EOL;
    }
    echo PHP_EOL;
    
    return $targetOrder;
}

function effectiveInitiativeOrder($a, $b)
{
    if ($a->effectivePower == $b->effectivePower) {
        return $a->initiative > $b->initiative ? -1 : 1;
    } else {
        return $a->effectivePower > $b->effectivePower ? -1 : 1;
    }
}

function initiativeOrder($a, $b)
{
    if ($a->initiative == $b->initiative) {
        return 0;
    } else {
        return $a->initiative > $b->initiative ? -1 : 1;
    }
}

function getEnemyGroups($armies, $currentTeam)
{
    $enemyGroups = array_filter($armies, function ($elem) use ($currentTeam) {
        return $elem->team !== $currentTeam;
    });

    return $enemyGroups;
}

function status($armies)
{
    echo "------------------- STATUS --------------------" . PHP_EOL;
    foreach (['immune' => getEnemyGroups($armies, 'infection'), 'infection' => getEnemyGroups($armies, 'immune')] as $team => $groups) {
        echo "$team:" . PHP_EOL;
        foreach ($groups as $k => $group) {
            echo "Group {$group->groupNumber} contains " . $group->units . " units.  EP: " . $group->effectivePower . PHP_EOL;
        }
    }
    echo PHP_EOL;
}

function boostImmune(&$armies, $boost)
{
    echo "............................... BOOSTING ARMY $boost .........................." . PHP_EOL;
    foreach ($armies as $k => $army) {
        if ($army->team == "immune") {
            $armies[$k]->attack += $boost;
            $armies[$k]->effectivePower = $armies[$k]->units * $armies[$k]->attack;
        }
    }
}

class immuneArmy extends armies
{
    public function __construct($data)
    {
        $this->team = "immune";
        parent::__construct($data);
    }
}

class infectionArmy extends armies
{
    public function __construct($data)
    {
        $this->team = "infection";
        parent::__construct($data);
    }
}

class armies
{
    public $team;
    public $units;
    public $hitPoints;
    public $attack;
    public $attackType;
    public $initiative;
    public $effectivePower;
    public $immunities = [];
    public $weakness = [];
    public $groupNumber;

    public function __construct($team, $data, $immuneGroupNum, $infectionGroupNum)
    {
        $this->team = $team;
        $this->units = $data[0];
        $this->hitPoints = $data[1];
        $this->attack = $data[3];
        $this->attackType = $data[4];
        $this->initiative = $data[5];
        $this->effectivePower = $this->units * $this->attack;
        foreach ($data[6] as $v) {
            if ($v['vulnerability'] == 'immune') {
                foreach ($v['types'] as $type) {
                    $this->immunities[] = $type;
                }
            } else {
                foreach ($v['types'] as $type) {
                    $this->weakness[] = $type;
                }
            }
        }
        $this->groupNumber = $team == 'immune' ? $immuneGroupNum : $infectionGroupNum;
    }

    public function damageToGroup($enemyGroup)
    {
        $damageMultiplier = 1;
        if (in_array($this->attackType, $enemyGroup->immunities)) {
            $damageMultiplier = 0;
        }
        if (in_array($this->attackType, $enemyGroup->weakness)) {
            $damageMultiplier = 2;
        }

        return $this->effectivePower * $damageMultiplier;
    }

    public function defend($attackingGroup)
    {
        $currentUnits = $this->units;
        $attackingDamage = $attackingGroup->damageToGroup($this);
        $numberKilled = floor($attackingDamage / $this->hitPoints);
        $this->units = max(0, $this->units - $numberKilled);
        $this->effectivePower = $this->units * $this->attack;

        return $currentUnits - $this->units;
    }

    public function isEnemy($group)
    {
        return $this->team == $group->team;
    }
}
