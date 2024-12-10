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
        if (! $validUpdate) {
            $sortedUpdate = sortUpdate($update, $beforeThan, $afterThan);
            // find the middle number and add to total
            $result += $sortedUpdate[floor(count($sortedUpdate) / 2)];
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

function sortUpdate(array $update, array $beforeThan, array $afterThan): array
{
    // sort the invalid update
    $sortedUpdate = [];
    foreach($update as $pageKey => $page) {
        if ($pageKey == 0) {
            $sortedUpdate[] = $page;
            continue;
        }
        // go backwards through the sorted update and inset at the correct point
        for ($i = count($sortedUpdate) - 1; $i >= 0; $i--) {
            // should page be before $i? if not it can be added to the end
            if (! (isset($beforeThan[$page]) && in_array($sortedUpdate[$i], $beforeThan[$page]))) {
                array_splice($sortedUpdate, $i + 1, 0, $page);
                break;
            }
            if ($i === 0) {
                // add to the start
                array_unshift($sortedUpdate, $page);
            }
        }
    }

    return $sortedUpdate;
}
