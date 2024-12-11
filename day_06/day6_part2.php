<?php

/**
 * Part 2 Solution:
 * - Record the current position in steps
 * - When adding a new step, if any existing steps contain the same coordinates AND rotation, then this is a time loop
 * - For each grid position that doesn't contain an obstacle or guard, add an obstacle and run moveGuard()
 * - If it resulted in a time loop then increment result
 */

$solution = new Day6Part2(__DIR__ . '/input.txt');
echo $solution->loopsEncountered;

class Day6Part2
{

    protected array $defaultGrid = [];
    protected array $startingPosition = [];
    public int $loopsEncountered = 0;

    public function __construct(string $inputFilepath)
    {
        $this->defaultGrid = $this->generateGrid(file($inputFilepath, FILE_IGNORE_NEW_LINES));
        foreach ($this->defaultGrid as $rowKey => $row) {
            foreach ($row as $columnKey => $column) {
                if ($column == '#' || $column === '^') continue;
                // Change this coordinate to an obstacle and re-run guard movement
                $grid = $this->defaultGrid;
                $grid[$rowKey][$columnKey] = '#';
                $guardMovement = new GuardMovement($grid, $this->startingPosition);
                if ($guardMovement->encounteredLoop) {
                    $this->loopsEncountered++;
                }
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
            }
        }
        return $grid;
    }
}

class GuardMovement
{
    // all grid positions array<row, col>
    protected array $grid;
    // starting position array<row, col>
    protected array $startingPosition = [];
    // current position of the guard as coordinate array<row, col>
    protected array $currentPosition = [];
    protected string $currentRotation = '^';
    // list of steps taken by the guard as string representation of coordinates "row,col,rotation"
    protected array $steps = [];
    public bool $encounteredLoop = false;

    public function __construct(array $grid, array $startingPosition)
    {
        $this->grid = $grid;
        $this->startingPosition = $startingPosition;
        $this->currentPosition = $this->startingPosition;
        $this->steps["{$this->currentPosition[0]},{$this->currentPosition[1]},{$this->currentRotation}"] = true;
        $this->moveGuard();
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
                // If a guard encounters an exact same position and rotation that they have already done, then
                // they are stuck in a loop
                $nextSpaceString = "{$this->currentPosition[0]},{$this->currentPosition[1]},{$this->currentRotation}";
                if (isset($this->steps[$nextSpaceString])) {
                    $this->encounteredLoop = true;
                    return;
                }
                $this->steps["{$this->currentPosition[0]},{$this->currentPosition[1]},{$this->currentRotation}"] = true;
            }
        }
    }
}
