<?php
include __DIR__ . '/utils.php';

gc_disable();
ini_set('memory_limit', '1024M');

$file = fopen('day21.txt', 'r');
$instructions = [];
$ip = null;
while (($line = fgets($file)) !== false) {
    if (is_null($ip)) {
        preg_match("/ip (\d+)/", $line, $matches);
        $ip = $matches[1];
    } else {
        preg_match("/(\w+) (\d+) (\d+) (\d+)/", $line, $matches);
        array_shift($matches);
        $instructions[] = $matches;
    }
}

$opCode = new opCodes;
$opCode->setRegister([9959629, 0, 0, 0, 0, 0]);
$opCode->setInstructionPointer($ip);

while (true) {
    $instruction = $instructions[$opCode->getIp()];
    echo "ip=" . $opCode->getIp() . " ";
    echo $opCode->printRegister() . " " . implode(" ", $instruction) . " ";
    $command = $instruction[0];
    $opCode->instruction($instruction)->{$command}();
    echo $opCode->printRegister() . PHP_EOL;
    $opCode->updateIp();
    if (! isset($instructions[$opCode->getIp()])) {
        break;
    }
    if ($opCode->getIp() == 30) {
        echo $opCode->printRegister();
    }
}

echo $opCode->printRegister();


class opCodes
{
    public $register;
    public $instructions;
    public $expectedResult;
    public $opCode;
    public $inputA;
    public $inputB;
    public $output;
    public $instructionPointer;
    
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

    public function printRegister()
    {
        echo "[" . implode(", ", $this->register) . "]";
    }
    
    public function setRegister($register)
    {
        $this->register = $register;

        return $this;
    }

    public function setInstructionPointer($ip)
    {
        $this->instructionPointer = $ip;

        return $this;
    }

    public function getIp()
    {
        return $this->register[$this->instructionPointer];
    }

    public function updateIp()
    {
        $this->register[$this->instructionPointer]++;
    }

    public function instruction($instruction)
    {
        $this->inputA = (int)$instruction[1];
        $this->inputB = (int)$instruction[2];
        $this->output = (int)$instruction[3];

        return $this;
    }

    public function addr()
    {
        $this->register[$this->output] = $this->register[$this->inputA] + $this->register[$this->inputB];
        return $this->register;
    }

    public function addi()
    {
        $this->register[$this->output] = $this->register[$this->inputA] + $this->inputB;
        return $this->register;
    }

    public function mulr()
    {
        $this->register[$this->output] = $this->register[$this->inputA] * $this->register[$this->inputB];
        return $this->register;
    }

    public function muli()
    {
        $this->register[$this->output] = $this->register[$this->inputA] * $this->inputB;
        return $this->register;
    }

    public function banr()
    {
        $this->register[$this->output] = $this->register[$this->inputA] & $this->register[$this->inputB];
        return $this->register;
    }

    public function bani()
    {
        $this->register[$this->output] = $this->register[$this->inputA] & $this->inputB;
        return $this->register;
    }

    public function borr()
    {
        $this->register[$this->output] = $this->register[$this->inputA] | $this->register[$this->inputB];
        return $this->register;
    }

    public function bori()
    {
        $this->register[$this->output] = $this->register[$this->inputA] | $this->inputB;
        return $this->register;
    }

    public function setr()
    {
        $this->register[$this->output] = $this->register[$this->inputA];
        return $this->register;
    }

    public function seti()
    {
        $this->register[$this->output] = $this->inputA;
        return $this->register;
    }

    public function gtir()
    {
        $this->register[$this->output] = $this->inputA > $this->register[$this->inputB] ? 1 : 0;
        return $this->register;
    }

    public function gtri()
    {
        $this->register[$this->output] = $this->register[$this->inputA] > $this->inputB ? 1 : 0;
        return $this->register;
    }

    public function gtrr()
    {
        $this->register[$this->output] = $this->register[$this->inputA] > $this->register[$this->inputB] ? 1 : 0;
        return $this->register;
    }

    public function eqir()
    {
        $this->register[$this->output] = $this->inputA == $this->register[$this->inputB] ? 1 : 0;
        return $this->register;
    }

    public function eqri()
    {
        $this->register[$this->output] = $this->register[$this->inputA] == $this->inputB ? 1 : 0;
        return $this->register;
    }

    public function eqrr()
    {
        $this->register[$this->output] = $this->register[$this->inputA] == $this->register[$this->inputB] ? 1 : 0;
        return $this->register;
    }
}
