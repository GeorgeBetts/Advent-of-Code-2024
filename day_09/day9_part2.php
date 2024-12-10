<?php

$input = str_split(file_get_contents(__DIR__ . '/input.txt'));

$storage = [];
$fileId = 0;
$fileSizes = [];
$totalBlockCount = 0;
for ($i = 0; $i < count($input); $i++) {
    $val = ($i == 0 || ($i % 2) == 0) ? $fileId : '.';
    array_push($storage, ...array_fill(0, $input[$i], $val));
    if ($val !== '.') {
        $fileSizes[$fileId] = intval($input[$i]);
        $totalBlockCount += $input[$i];
        $fileId++;
    }
}

// work from the backwards 'in', keeping a pointer the left most available space
$filesMoved = [];
for ($i = count($storage) - 1; $i >= 0; $i--) {
    // skip existing free space
    if ($storage[$i] === '.') continue;
    // skip if file already moved
    if (isset($filesMoved[$storage[$i]])) continue;

    // get the next available free space
    $spaceRequired = $fileSizes[$storage[$i]];
    $freeSpacePointer = 0;
    $spaceFound = false;
    while ($spaceFound == false && $freeSpacePointer < ($i - $spaceRequired)) {
        while ($storage[$freeSpacePointer] !== '.') $freeSpacePointer++;
        // break if there is no space found by the file
        if ($freeSpacePointer >= $i) break;
        // if the next $spaceRequired blocks are not the right size then skip past them
        for ($n = 1; $n < $spaceRequired; $n++) {
            if ($storage[$freeSpacePointer + $n] !== '.') {
                $freeSpacePointer += $n;
                continue 2;
            }
        }
        $spaceFound = true;
    }
    if (! $spaceFound) {
        // no room to move the file, leave it where it is and skip it
        continue;
    }
    // swap all blocks of the file into the identified free space
    $filesMoved[$storage[$i]] = true;
    for ($n = 0; $n < $spaceRequired; $n++) {
        $storage[$freeSpacePointer + $n] = $storage[$i - $n];
        $storage[$i - $n] = '.';
    }
}

// calculate checksum
$checksum = 0;
for ($i = 0; $i < count($storage); $i++) {
    if ($storage[$i] === '.') continue;
    $checksum += $i * $storage[$i];
}


echo $checksum;
