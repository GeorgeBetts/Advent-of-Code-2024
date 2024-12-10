<?php

$input = str_split(file_get_contents(__DIR__ . '/input.txt'));

$storage = [];
$fileId = 0;
$totalBlockCount = 0;
for ($i = 0; $i < count($input); $i++) {
    $val = ($i == 0 || ($i % 2) == 0) ? $fileId : '.';
    array_push($storage, ...array_fill(0, $input[$i], $val));
    if ($val !== '.') {
        $fileId++;
        $totalBlockCount += $input[$i];
    }
}

// work from the backwards 'in', keeping a pointer the left most available space
$freeSpacePointer = 0;
for ($i = count($storage) - 1; $i >= $totalBlockCount; $i--) {
    // skip existing free space
    if ($storage[$i] === '.') continue;
    // get the next available free space
    while ($storage[$freeSpacePointer] !== '.') $freeSpacePointer++;
    // swap the value at $i with the free space
    $storage[$freeSpacePointer] = $storage[$i];
    $storage[$i] = '.';
}

// calculate checksum
$checksum = 0;
foreach($storage as $key => $value) {
    if ($value === '.') break;
    $checksum += $key * $value;
}

echo $checksum;
