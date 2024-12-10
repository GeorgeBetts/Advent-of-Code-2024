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
        // add the antenna as an antinode
        $antinodes[$antenna[0] .','. $antenna[1]] = true;
        // compare the antenna against each other antenna
        foreach ($plot as $key2 => $comparison) {
            if ($key2 <= $key1) continue;
            if ($antenna == $comparison) continue;

            $rowOperation = ($antenna[0] - $comparison[0]);
            $colOperation = ($antenna[1] - $comparison[1]);

            $currentRow = $antenna[0] + $rowOperation;
            $currentCol = $antenna[1] + $colOperation;

            // while in bounds of the grid
            while ((1 <= $currentRow) && ($currentRow <= $rowLimit) && (1 <= $currentCol) && ($currentCol <= $colLimit)) {
                $antinodes[$currentRow .','. $currentCol] = true;
                $currentRow += $rowOperation;
                $currentCol += $colOperation;
            }

            // do the same for the other direction
            $currentRow = $comparison[0] - $rowOperation;
            $currentCol = $comparison[1] - $colOperation;

            while ((1 <= $currentRow) && ($currentRow <= $rowLimit) && (1 <= $currentCol) && ($currentCol <= $colLimit)) {
                $antinodes[$currentRow .','. $currentCol] = true;
                $currentRow -= $rowOperation;
                $currentCol -= $colOperation;
            }
        }
    }
}

echo count($antinodes);
