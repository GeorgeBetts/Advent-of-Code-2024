<?php

$solution = new Day4Part2(__DIR__ . '/input.txt');
echo $solution->countOccurrences();

class Day4Part2
{
    protected array $grid;

    public function __construct(string $inputFilepath)
    {
        $this->grid = $this->generateGrid(file($inputFilepath, FILE_IGNORE_NEW_LINES));
    }

    /**
     * Counts the occurrences of a cross of words in a word search
     *
     * @param string $word
     * @return int
     */
    public function countOccurrences(string $word = 'MAS'): int
    {
        if (strlen($word) != 3) {
            return 0;
        }
        $occurrences = 0;
        $search = str_split($word);
        $wordLenOffset = 2;
        foreach ($this->grid as $row => $rowVal) {
            foreach ($rowVal as $col => $colVal) {
                // Check for the middle of the word being searched
                if ($colVal != $search[1]) {
                    continue;
                }

                // Once we have the center letter search the occurrences of the word going across the current central
                // coordinate. SE, SW, NE and NW are acceptable directions, the word must exist on two of these
                // directions to make an X
                $xOccurrences = 0;

                if (isset($this->grid[$row - 1][$col - 1]) && isset($this->grid[$row + 1][$col + 1]) && $this->grid[$row - 1][$col - 1] == $search[0] && $this->grid[$row + 1][$col + 1] == $search[2]) {
                    $xOccurrences++;
                }
                if (isset($this->grid[$row - 1][$col + 1]) && isset($this->grid[$row + 1][$col - 1]) && $this->grid[$row - 1][$col + 1] == $search[0] && $this->grid[$row + 1][$col - 1] == $search[2]) {
                    $xOccurrences++;
                }
                if (isset($this->grid[$row + 1][$col - 1]) && isset($this->grid[$row - 1][$col + 1]) && $this->grid[$row + 1][$col - 1] == $search[0] && $this->grid[$row - 1][$col + 1] == $search[2]) {
                    $xOccurrences++;
                }
                if (isset($this->grid[$row + 1][$col + 1]) && isset($this->grid[$row - 1][$col - 1]) && $this->grid[$row + 1][$col + 1] == $search[0] && $this->grid[$row - 1][$col - 1] == $search[2]) {
                    $xOccurrences++;
                }

                if ($xOccurrences >= 2) {
                    $occurrences++;
                }
            }
        }

        return $occurrences;
    }

    private function generateGrid(array $input): array
    {
        $grid = [];
        foreach ($input as $row) {
            $grid[] = str_split($row);
        }
        return $grid;
    }
}
