<?php

$input = file(__DIR__.'/input.txt', FILE_IGNORE_NEW_LINES);

// Create two hashmaps
// beforeThan - hash key must be before the values
$beforeThan = [];
// afterThan - hash key must be after the values
$afterThan = [];
$result = 0;
foreach ($input as $line) {
    if ($line == "") continue;
    if (str_contains($line, '|')) {
        // Add values to hash map
        $values = array_map(fn($val) => intval($val), explode('|', $line));
        $beforeThan[$values[0]][] = $values[1];
        $afterThan[$values[1]][] = $values[0];
    } else {
        $update = explode(',', $line);
        $validUpdate = isSortedCorrectly($update, $beforeThan, $afterThan);
        if ($validUpdate) {
            // find the middle number and add to total
            $result += $update[count($update) / 2];
        }
    }
}

echo $result;

function isSortedCorrectly(array $update, array $beforeThan, array $afterThan): bool
{
    foreach ($update as $pageKey => $page) {
        // Compare the page against other pages in the update
        foreach ($update as $comparisonKey => $comparisonPage) {
            if ($page == $comparisonPage) continue;
            // If comparisonPage is currently before page in the sequence, check if it should be after
            if ($comparisonKey < $pageKey && isset($afterThan[$comparisonPage]) && in_array($page, $afterThan[$comparisonPage])) {
                return false;
            }
            // If comparisonPage is currently after page in the sequence, check if it should be before
            if ($comparisonKey > $pageKey && isset($beforeThan[$comparisonPage]) && in_array($page, $beforeThan[$comparisonPage])) {
                return false;
            }
        }
    }

    return true;
}
