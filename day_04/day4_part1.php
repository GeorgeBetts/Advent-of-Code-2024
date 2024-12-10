<?php

$solution = new Day4Part1(__DIR__ . '/input.txt');
echo $solution->countOccurrences();

class Day4Part1
{
    protected array $grid;

    public function __construct(string $inputFilepath)
    {
        $this->grid = $this->generateGrid(file($inputFilepath, FILE_IGNORE_NEW_LINES));
    }

    /**
     * Counts the occurrences of a word in a word search
     *
     * @param string $word
     * @return int
     */
    public function countOccurrences(string $word = 'XMAS'): int
    {
        $occurrences = 0;
        $search = str_split($word);
        $wordLenOffset = strlen($word) - 1;
        foreach ($this->grid as $row => $rowVal) {
            foreach ($rowVal as $col => $colVal) {
                // Check for the start of the word being searched
                if ($colVal != $search[0]) {
                    continue;
                }

                // Check if the word could be in each 'cardinal' direction, start with North
                $occurrences += $this->search($search, $row, $col, $row - $wordLenOffset, $col) ? 1 : 0;
                $occurrences += $this->search($search, $row, $col, $row - $wordLenOffset, $col + $wordLenOffset) ? 1 : 0;
                $occurrences += $this->search($search, $row, $col, $row, $col + $wordLenOffset) ? 1 : 0;
                $occurrences += $this->search($search, $row, $col, $row + $wordLenOffset, $col + $wordLenOffset) ? 1 : 0;
                $occurrences += $this->search($search, $row, $col, $row + $wordLenOffset, $col) ? 1 : 0;
                $occurrences += $this->search($search, $row, $col, $row + $wordLenOffset, $col - $wordLenOffset) ? 1 : 0;
                $occurrences += $this->search($search, $row, $col, $row, $col - $wordLenOffset) ? 1 : 0;
                $occurrences += $this->search($search, $row, $col, $row - $wordLenOffset, $col - $wordLenOffset) ? 1 : 0;
            }
        }

        return $occurrences;
    }

    /**
     * Searches if a word occurs between a grid position and a target position
     */
    private function search(array $word, int $row, int $col, int $targetRow, int $targetCol): bool
    {
        if (! isset($this->grid[$targetRow][$targetCol])) {
            return false;
        }

        for ($i = 1; $i < count($word); $i++) {
            if ($this->grid[$this->getCoordinatesFromTarget($row, $targetRow, $i)][$this->getCoordinatesFromTarget($col, $targetCol, $i)] !== $word[$i]) {
                return false;
            }
        }

        return true;
    }

    private function getCoordinatesFromTarget(int $current, int $target, int $offset): int
    {
        if ($target < $current) {
            return $current - $offset;
        }

        if ($target > $current) {
            return $current + $offset;
        }

        return $current;
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
