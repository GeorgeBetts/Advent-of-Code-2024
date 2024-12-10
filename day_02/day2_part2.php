<?php


$values = file(__DIR__ . '/input.txt', FILE_IGNORE_NEW_LINES);

$safeReports = 0;
foreach ($values as $key =>  $report) {
    $levels = explode(' ', $report);
    $safe = isSafe($levels);
    if (!$safe) {
        for ($i = 0; $i < count($levels); $i++) {
            $spliced = $levels;
            array_splice($spliced, $i, 1);
            if (isSafe($spliced)) {
                $safe = true;
                break;
            }
        }
    }
    $safeReports += $safe ? 1 : 0;
}

echo $safeReports;

function isSafe(array $levels): bool
{
    $increase = $levels[0] < $levels[1];
    for ($key = 0; $key < count($levels); $key++) {
        if (!isset($levels[$key + 1])) {
            continue;
        }
        if (doesItFail($levels[$key], $levels[$key + 1], $increase)) {
            return false;
        };
    }
    return true;
}

function doesItFail(int $level, int $comparisonLevel, bool $increase): bool
{
    // Neither increase or decrease is a fail
    if ($level === $comparisonLevel) return true;
    // Increase when it should be a decrease is a fail
    if ($level < $comparisonLevel && $increase === false) return true;
    // Decrease when it should be an increase is a fail
    if ($level > $comparisonLevel && $increase === true) return true;
    if (abs($level - $comparisonLevel) > 3) return true;

    return false;
}
