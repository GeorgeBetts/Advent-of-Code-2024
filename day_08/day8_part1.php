<?php

$input = file(__DIR__.'/input.txt', FILE_IGNORE_NEW_LINES);

// first plot the coordinates
$plots = [];
foreach ($input as $rowKey => $row) {
    $spaces = str_split($row);
    foreach ($spaces as $colKey => $col) {
        if ($col !== '.') {
            $plots[$col][] = [$rowKey + 1, $colKey + 1];
        }
    }
}

// set grid limits
$rowLimit = count($input);
$colLimit = strlen($input[0]);

$antinodes = [];
foreach ($plots as $plot) {
    foreach ($plot as $key1 => $antenna) {
        // compare the antenna against each other antenna
        foreach ($plot as $key2 => $comparison) {
            if ($key2 <= $key1) continue;
            if ($antenna == $comparison) continue;

            $antinode1Row = $antenna[0] + ($antenna[0] - $comparison[0]);
            $antinode1Col = $antenna[1] + ($antenna[1] - $comparison[1]);
            if ((1 <= $antinode1Row) && ($antinode1Row <= $rowLimit) && (1 <= $antinode1Col) && ($antinode1Col <= $colLimit)) {
                $antinodes[$antinode1Row .','. $antinode1Col] = true;
            }

            $antinode2Row = $comparison[0] + ($comparison[0] - $antenna[0]);
            $antinode2Col = $comparison[1] + ($comparison[1] - $antenna[1]);
            if ((1 <= $antinode2Row) && ($antinode2Row <= $rowLimit) && (1 <= $antinode2Col) && ($antinode2Col <= $colLimit)) {
                $antinodes[$antinode2Row .','. $antinode2Col] = true;
            }
        }
    }
}

echo count($antinodes);
