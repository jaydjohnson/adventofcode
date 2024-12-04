<?php
include __DIR__ . '/utils.php';

gc_disable();
ini_set('memory_limit', '1024M');

$file = fopen('day16.txt', 'r');
$testCases = [];
$lineNum = 1;
while (($line = fgets($file)) !== false) {
    if ($lineNum % 4 == 1) {
        preg_match("/\[(\d+), (\d+), (\d+), (\d)\]/", $line, $matches);
        array_shift($matches);
        $test['before'] = $matches;
        //$test['before'] = preg_match("\[(\d+), (\d+), (\d+), (\d)\]", $line, $matches);
    } elseif ($lineNum % 4 == 2) {
        preg_match("/(\d+) (\d+) (\d+) (\d)/", $line, $matches);
        array_shift($matches);
        $test['instructions'] = $matches;
    } elseif ($lineNum % 4 == 3) {
        preg_match("/\[(\d+), (\d+), (\d+), (\d)\]/", $line, $matches);
        array_shift($matches);
        $test['after'] = $matches;
    } elseif ($lineNum % 4 == 0) {
        $testCases[] = $test;
    }

    $lineNum++;
}

// $opTests = [
//     'Addr' => new Addr,
//     'Addi' => new Addi,
//     'Mulr' => new Mulr,
//     'Banr' => new Banr,
//     'Bani' => new Bani,
//     'Borr' => new Borr,
//     'Bori' => new Bori,
//     'Setr' => new Setr,
//     'Seti' => new Seti,
//     'Gtir' => new Gtir,
//     'Gtri' => new Gtri,
//     'Gtrr' => new Gtrr,
//     'Eqir' => new Eqir,
//     'Eqri' => new Eqri,
//     'Eqrr' => new Eqrr,
// ];

$opCodes = new opCodes;
$foundCodes = [];
$foundCodeNames = [];

foreach ($testCases as &$test) {
    $successes = 0;
    $successfulCodes = [];
    $testOpCode = $test['instructions'][0];

    foreach ($opCodes->codeList as $k => $opTest) {
        $functionName = strtolower($opTest) . "Test";
        $result = $opCodes->extract($test)->{$functionName}();

        //echo "$opTest: " . ($result ? 'yes' : 'no') . PHP_EOL;
        $successes += $result ? 1 : 0;
        if ($result) {
            // $successfulCodes[] = ['name' => $opTest, 'code' => $testOpCode, 'key' => $k];
            $test['opcodes'][$opTest] = true;
        }
    }

    // $filteredCodes = array_filter($successfulCodes, function ($a) use ($foundCodes) {
    //     return ! in_array($a['name'], $foundCodes);
    // });

    if ($successes == 1) {
        // $foundCodes[] = ['code' => $successfulCodes[0]['code'], 'name' => $successfulCodes[0]['name']];
        // $foundCodeNames[] = $successfulCodes[0]['name'];
        // remove optest since we found the code
    }
    $test['successes'] = $successes;
}

$map = [];
while (count($map) < 16) {
    foreach ($testCases as $test) {
        if (count($test['opcodes']) == 1) {
            $name = array_keys($test['opcodes'])[0];
            $map[$test['instructions'][0]] = $name;
            //echo "Adding " . $name . " to map." . PHP_EOL;
            foreach (array_keys($testCases) as $k) {
                unset($testCases[$k]['opcodes'][$name]);
            }
        }
    }
}

print_r($map);

$file = fopen('day16p2.txt', 'r');
$instructions = [];
while (($line = fgets($file)) !== false) {
    preg_match("/(\d+) (\d+) (\d+) (\d)/", $line, $matches);
    array_shift($matches);
    $instructions[] = $matches;
}

$opCodes->register = [0, 0, 0, 0];
foreach ($instructions as $instruction) {
    $functionName = strtolower($map[$instruction[0]]) . "Test";
    echo $functionName;
    $opCodes->instruction($instruction)->{$functionName}();
}

print_r($opCodes->register);
// print_r($testCases);
// countSuccesses($testCases);

function countSuccesses($tests)
{
    $total = 0;
    foreach ($tests as $test) {
        $total += $test['successes'] == 1 ? 1 : 0;
    }
    echo "total: " . $total;
}

class opCodes
{
    public $register;
    public $instructions;
    public $expectedResult;
    public $opCode;
    public $inputA;
    public $inputB;
    public $output;
    public $found = false;
    public $codeList = ['Addr', 'Addi', 'Mulr', 'Muli', 'Banr', 'Bani', 'Borr', 'Bori', 'Setr', 'Seti', 'Gtir', 'Gtri', 'Gtrr', 'Eqir', 'Eqri', 'Eqrr'];
    public $foundCodes = [];
    
    public function __construct()
    {
    }

    public function compare()
    {
        if ($this->register == $this->expectedResult) {
            return true;
        }
        return false;
    }

    public function extract($data)
    {
        $this->register = $data['before'];
        $this->instructions = $data['instructions'];
        $this->expectedResult = $data['after'];
        $this->testOpCode = $this->instructions[0];
        $this->inputA = (int)$this->instructions[1];
        $this->inputB = (int)$this->instructions[2];
        $this->output = (int)$this->instructions[3];

        return $this;
    }

    public function instruction($instruction)
    {
        $this->testOpCode = $instruction[0];
        $this->inputA = (int)$instruction[1];
        $this->inputB = (int)$instruction[2];
        $this->output = (int)$instruction[3];

        return $this;
    }

    public function addrTest()
    {
        $this->register[$this->output] = $this->register[$this->inputA] + $this->register[$this->inputB];
        return $this->compare();
    }

    public function addiTest()
    {
        $this->register[$this->output] = $this->register[$this->inputA] + $this->inputB;
        return $this->compare();
    }

    public function mulrTest()
    {
        $this->register[$this->output] = $this->register[$this->inputA] * $this->register[$this->inputB];
        return $this->compare();
    }

    public function muliTest()
    {
        $this->register[$this->output] = $this->register[$this->inputA] * $this->inputB;
        return $this->compare();
    }

    public function banrTest()
    {
        $this->register[$this->output] = $this->register[$this->inputA] & $this->register[$this->inputB];
        return $this->compare();
    }

    public function baniTest()
    {
        $this->register[$this->output] = $this->register[$this->inputA] & $this->inputB;
        return $this->compare();
    }

    public function borrTest()
    {
        $this->register[$this->output] = $this->register[$this->inputA] | $this->register[$this->inputB];
        return $this->compare();
    }

    public function boriTest()
    {
        $this->register[$this->output] = $this->register[$this->inputA] | $this->inputB;
        return $this->compare();
    }

    public function setrTest()
    {
        $this->register[$this->output] = $this->register[$this->inputA];
        return $this->compare();
    }

    public function setiTest()
    {
        $this->register[$this->output] = $this->inputA;
        return $this->compare();
    }

    public function gtirTest()
    {
        $this->register[$this->output] = $this->inputA > $this->register[$this->inputB] ? 1 : 0;
        return $this->compare();
    }

    public function gtriTest()
    {
        $this->register[$this->output] = $this->register[$this->inputA] > $this->inputB ? 1 : 0;
        return $this->compare();
    }

    public function gtrrTest()
    {
        $this->register[$this->output] = $this->register[$this->inputA] > $this->register[$this->inputB] ? 1 : 0;
        return $this->compare();
    }

    public function eqirTest()
    {
        $this->register[$this->output] = $this->inputA == $this->register[$this->inputB] ? 1 : 0;
        return $this->compare();
    }

    public function eqriTest()
    {
        $this->register[$this->output] = $this->register[$this->inputA] == $this->inputB ? 1 : 0;
        return $this->compare();
    }

    public function eqrrTest()
    {
        $this->register[$this->output] = $this->register[$this->inputA] == $this->register[$this->inputB] ? 1 : 0;
        return $this->compare();
    }
}