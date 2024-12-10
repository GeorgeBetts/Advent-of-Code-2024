<?php

$input = file_get_contents(__DIR__.'/input.txt');

$mulInstructions = [];
preg_match_all("(mul\(\d{1,3},\d{1,3}\))", $input, $mulInstructions);

$result = 0;
foreach ($mulInstructions[0] as $instruction) {
    $result += eval('$result += '.$instruction.';');
}

echo $result;

function mul(int $inputOne, int $inputTwo): int
{
    return $inputOne * $inputTwo;
}
