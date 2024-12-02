<?php

$values = file(__DIR__.'/input.txt', FILE_IGNORE_NEW_LINES);

$leftList = [];
$rightList = [];

// Format into the two lists by exploding each line of the file into the two values
foreach ($values as $value) {
    $split = explode('   ', $value);
    $leftList[] = intval($split[0]);
    $rightList[] = intval($split[1]);
}

// Loop through the right list and create a hash map of occurrences
$rightListHash = [];
foreach ($rightList as $item) {
    if (! isset($rightListHash[$item])) {
        $rightListHash[$item] = 0;
    }
    $rightListHash[$item]++;
}

// Loop through left list and multiply it by the occurrences
$similarity = 0;
foreach ($leftList as $item) {
    if (isset($rightListHash[$item])) {
        $similarity += ($item * $rightListHash[$item]);
    }
}

echo $similarity;
