<?php

$values = file(__DIR__.'/input.txt', FILE_IGNORE_NEW_LINES);

$safeReports = 0;
foreach ($values as $report) {
    $safe = isSafe(explode(' ', $report));
    $safeReports += $safe ? 1 : 0;
}

echo $safeReports;

function isSafe(array $levels): bool
{
    $increase = null;
    foreach ($levels as $key => $level) {
        // Skip the first item
        if ($key === 0) continue;
        // Neither increase or decrease is a fail
        if ($level === $levels[$key - 1]) return false;
        // Increase when it should be a decrease is a fail
        if ($level > $levels[$key - 1] && $increase === false) return false;
        // Decrease when it should be an increase is a fail
        if ($level < $levels[$key - 1] && $increase === true) return false;
        // Set whether the report is increasing or decreasing
        if ($increase == null) {
            $increase = $level > $levels[$key - 1];
        }
        if (abs($level - $levels[$key - 1]) > 3) return false;
    }
    return true;
}
