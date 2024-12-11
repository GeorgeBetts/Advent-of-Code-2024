<?php

$solution = new Day6Part1(__DIR__ . '/input.txt');
echo $solution->countSteps();

class Day6Part1
{

    // all grid positions array<row, col>
    protected array $grid;
    // starting position array<row, col>
    protected array $startingPosition = [];
    // current position of the guard as coordinate array<row, col>
    protected array $currentPosition = [];
    protected string $currentRotation = '^';
    // list of steps taken by the guard as coordinates array<row, col>
    protected array $steps = [];

    public function __construct(string $inputFilepath)
    {
        $this->grid = $this->generateGrid(file($inputFilepath, FILE_IGNORE_NEW_LINES));
        $this->moveGuard();
    }

    public function countSteps(): int
    {
        return count(array_unique($this->steps, SORT_REGULAR)) - 1;
    }

    /**
     * Move the guard and fill in the steps they move
     *
     * @return void
     */
    private function moveGuard(): void
    {
        // while in the confines of the grid
        while (isset($this->grid[$this->currentPosition[0]][$this->currentPosition[1]])) {
            // move based on current position
            $nextSpace = [];
            $nextRotation = '^';
            switch($this->currentRotation) {
                case '^':
                    $nextSpace = [$this->currentPosition[0] - 1, $this->currentPosition[1]];
                    $nextRotation = '>';
                    break;
                case '>':
                    $nextSpace = [$this->currentPosition[0], $this->currentPosition[1] + 1];
                    $nextRotation = 'v';
                    break;
                case 'v':
                    $nextSpace = [$this->currentPosition[0] + 1, $this->currentPosition[1]];
                    $nextRotation = '<';
                    break;
                case '<':
                    $nextSpace = [$this->currentPosition[0], $this->currentPosition[1] - 1];
                    break;
            }

            // check if next space contains an obstacle
            if (isset($this->grid[$nextSpace[0]][$nextSpace[1]]) && $this->grid[$nextSpace[0]][$nextSpace[1]] === '#') {
                // update current rotation - new next space will be calculated next loop
                $this->currentRotation = $nextRotation;
            } else {
                $this->currentPosition = $nextSpace;
                $this->steps[] = $this->currentPosition;
            }
        }
    }

    private function generateGrid(array $input): array
    {
        $grid = [];
        foreach ($input as $rowKey => $row) {
            $grid[] = str_split($row);
            // get starting position
            if (str_contains($row, '^')) {
                $this->startingPosition = [$rowKey, strpos($row, '^')];
                $this->steps[] = $this->startingPosition;
                $this->currentPosition = $this->startingPosition;
            }
        }
        return $grid;
    }
}
