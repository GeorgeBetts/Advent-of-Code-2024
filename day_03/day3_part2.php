<?php

$input = file_get_contents(__DIR__.'/input.txt');

$mulInstructions = [];
preg_match_all("/(mul\(\d{1,3},\d{1,3}\))|(do\(\))|(don't\(\))/", $input, $mulInstructions);

$result = 0;
$doMultiply = true;
foreach ($mulInstructions[0] as $instruction) {
    switch ($instruction) {
        case "do()":
            $doMultiply = true;
            continue 2;
        case "don't()":
            $doMultiply = false;
            continue 2;
        default:
            break;
    }
    if ($doMultiply) {
        $result += eval('$result += ' . $instruction . ';');
    }
}

echo $result;

function mul(int $inputOne, int $inputTwo): int
{
    return $inputOne * $inputTwo;
}
