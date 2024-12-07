<?php

$input = file(__DIR__.'/input.txt', FILE_IGNORE_NEW_LINES);

$result = 0;

foreach ($input as $line) {
    $split = explode(':', $line);
    $target = intval($split[0]);
    $items = array_map(fn($i) => intval($i),explode(' ', trim($split[1])));

    $equations = [];
    $equations[0] = [$items[0]];
    for ($i = 1; $i < count($items); $i++) {
        $equations[$i] = [];
        foreach($equations[$i - 1] as $equation) {
            $equations[$i][] = $equation + $items[$i];
            $equations[$i][] = $equation * $items[$i];
        }
        if ($i == count($items) - 1 && in_array($target, $equations[$i])) {
            $result += $target;
        }
    }
}

echo $result;
