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

print_r($leftList);

// Sort the lists, so we can process them quicker (saves having to do a min() every iteration, and prevents
// removing items from the array
rsort($leftList);
rsort($rightList);


$items = count($values);
$difference = 0;
for ($i = 0; $i < $items; $i++) {
    $minLeft = array_pop($leftList);
    $minRight = array_pop($rightList);
    $difference += abs($minLeft - $minRight);
    echo "Adding difference of ".$minLeft." ".$minRight.PHP_EOL;
}

echo $difference;
