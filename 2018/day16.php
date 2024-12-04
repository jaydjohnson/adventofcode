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

$opTests = ['Addr', 'Addi', 'Mulr', 'Muli', 'Banr', 'Bani', 'Borr', 'Bori', 'Setr', 'Seti', 'Gtir', 'Gtri', 'Gtrr', 'Eqir', 'Eqri', 'Eqrr'];
// $tests = [
//     ['before' => [3, 2, 1, 1], 'after' => [3, 2, 2, 1], 'instructions' => [9, 2, 1, 2]],
//     ['before' => [0, 3, 3, 0], 'after' => [0, 0, 3, 0], 'instructions' => [5, 0, 2, 1]],
// ];

//$o = new Addi($tests);
// echo $o->test();

foreach ($testCases as &$test) {
    $successes = 0;
    foreach ($opTests as $opTest) {
        $opCode = new $opTest($test);
        $result = $opCode->test();
        //echo "$opTest: " . ($result ? 'yes' : 'no') . PHP_EOL;
        $successes += $result ? 1 : 0;
    }
    $test['successes'] = $successes;
}

countSuccesses($testCases);

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
    
    public function __construct($test)
    {
        $this->register = $test['before'];
        $this->instructions = $test['instructions'];
        $this->expectedResult = $test['after'];
        $this->opCode = $this->instructions[0];
        $this->inputA = $this->instructions[1];
        $this->inputB = $this->instructions[2];
        $this->output = $this->instructions[3];
    }

    public function compare()
    {
        if ($this->register == $this->expectedResult) {
            return true;
        }
        return false;
    }
}

class Addr extends opCodes
{
    public function test()
    {
        $this->register[$this->output] = $this->register[$this->inputA] + $this->register[$this->inputB];
        return $this->compare();
    }
}

class Addi extends opCodes
{
    public function test()
    {
        $this->register[$this->output] = $this->register[$this->inputA] + $this->inputB;
        return $this->compare();
    }
}

class Mulr extends opCodes
{
    public function test()
    {
        $this->register[$this->output] = $this->register[$this->inputA] * $this->register[$this->inputB];
        return $this->compare();
    }
}

class Muli extends opCodes
{
    public function test()
    {
        $this->register[$this->output] = $this->register[$this->inputA] * $this->inputB;
        return $this->compare();
    }
}

class Banr extends opCodes
{
    public function test()
    {
        $this->register[$this->output] = $this->register[$this->inputA] & $this->register[$this->inputB];
        return $this->compare();
    }
}

class Bani extends opCodes
{
    public function test()
    {
        $this->register[$this->output] = $this->register[$this->inputA] & $this->inputB;
        return $this->compare();
    }
}

class Borr extends opCodes
{
    public function test()
    {
        $this->register[$this->output] = $this->register[$this->inputA] | $this->register[$this->inputB];
        return $this->compare();
    }
}

class Bori extends opCodes
{
    public function test()
    {
        $this->register[$this->output] = $this->register[$this->inputA] | $this->inputB;
        return $this->compare();
    }
}

class Setr extends opCodes
{
    public function test()
    {
        $this->register[$this->output] = $this->register[$this->inputA];
        return $this->compare();
    }
}

class Seti extends opCodes
{
    public function test()
    {
        $this->register[$this->output] = $this->inputA;
        return $this->compare();
    }
}

class Gtir extends opCodes
{
    public function test()
    {
        $this->register[$this->output] = $this->inputA > $this->register[$this->inputB] ? 1 : 0;
        return $this->compare();
    }
}

class Gtri extends opCodes
{
    public function test()
    {
        $this->register[$this->output] = $this->register[$this->inputA] > $this->inputB ? 1 : 0;
        return $this->compare();
    }
}

class Gtrr extends opCodes
{
    public function test()
    {
        $this->register[$this->output] = $this->register[$this->inputA] > $this->register[$this->inputB] ? 1 : 0;
        return $this->compare();
    }
}

class Eqir extends opCodes
{
    public function test()
    {
        $this->register[$this->output] = $this->inputA == $this->register[$this->inputB] ? 1 : 0;
        return $this->compare();
    }
}

class Eqri extends opCodes
{
    public function test()
    {
        $this->register[$this->output] = $this->register[$this->inputA] == $this->inputB ? 1 : 0;
        return $this->compare();
    }
}

class Eqrr extends opCodes
{
    public function test()
    {
        $this->register[$this->output] = $this->register[$this->inputA] == $this->register[$this->inputB] ? 1 : 0;
        return $this->compare();
    }
}
