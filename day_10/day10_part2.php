<?php

$map = new Map(__DIR__.'/input.txt');
echo $map->getTotalPathsToSummit();

class Map
{
    /**
     * List of top level trees
     *
     * @var array<Node>
     */
    protected array $trailheads = [];

    /**
     * Map / Grid references
     *
     * @var array<int, int>
     */
    protected array $grid = [];

    public function __construct(string $inputFilepath)
    {
        $this->grid = $this->generateGrid(file($inputFilepath, FILE_IGNORE_NEW_LINES));
    }

    public function getTotalPathsToSummit(): int
    {
        $result = 0;
        foreach ($this->trailheads as $trailhead) {
            $result += $trailhead->countPathsToSummit();
        }
        return $result;
    }

    private function generateGrid(array $input): array
    {
        $grid = [];
        foreach ($input as $rowKey => $line) {
            $grid[$rowKey] = [];
            $row = array_map(fn($v) => intval($v),str_split($line));
            foreach ($row as $colKey => $col) {
                $node = new Node($rowKey, $colKey, $col);
                $grid[$rowKey][$colKey] = $node;
                if ($node->isTrailhead()) {
                    $this->trailheads[] = $node;
                }
            }
        }
        return $this->buildNodes($grid);
    }

    private function buildNodes(array $grid): array
    {
        foreach ($grid as $rowKey => $row) {
            foreach ($row as $colKey => $node) {
                // set adjacent nodes for valid paths only
                if (isset($grid[$rowKey - 1][$colKey]) && $grid[$rowKey - 1][$colKey]->value === $node->value + 1) {
                    $node->north = $grid[$rowKey - 1][$colKey];
                }
                if (isset($row[$colKey + 1]) && $row[$colKey + 1]->value === $node->value + 1) {
                    $node->east = $row[$colKey + 1];
                }
                if (isset($grid[$rowKey + 1][$colKey]) && $grid[$rowKey + 1][$colKey]->value === $node->value + 1) {
                    $node->south = $grid[$rowKey + 1][$colKey];
                }
                if (isset($row[$colKey - 1]) && $row[$colKey - 1]->value === $node->value + 1) {
                    $node->west = $row[$colKey - 1];
                }
            }
        }

        return $grid;
    }
}

class Node
{
    public int $row;
    public int $col;
    public int $value;
    public ?Node $north = null;
    public ?Node $east = null;
    public ?Node $south = null;
    public ?Node $west = null;

    public function __construct(int $row, int $col, int $value)
    {
        $this->row = $row;
        $this->col = $col;
        $this->value = $value;
    }

    public function isTrailhead(): bool
    {
        return $this->value === 0;
    }

    public function countPathsToSummit(int $result = 0, ?Node $node = null): int
    {
        if ($node === null) {
            $node = $this;
        }

        // summit reached
        if ($node->value === 9) {
            $result++;
        }

        if ($node->north !== null) {
            $result = self::countPathsToSummit($result, $node->north);
        }
        if ($node->east !== null) {
            $result = self::countPathsToSummit($result, $node->east);
        }
        if ($node->south !== null) {
            $result = self::countPathsToSummit($result, $node->south);
        }
        if ($node->west !== null) {
            $result = self::countPathsToSummit($result, $node->west);
        }

        return $result;
    }
}
