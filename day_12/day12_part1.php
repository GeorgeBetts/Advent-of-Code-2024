<?php

include __DIR__.'/../PuzzleTimer.php';

$timer = new PuzzleTimer();
$farm = new Farm(__DIR__.'/input.txt');
$totalCost = 0;
foreach ($farm->regions as $region) {
    $totalCost += $region->getCost();
}
echo $totalCost.PHP_EOL;
$timer->output();

class Farm
{
    /**
     * @var array<int, int>
     */
    protected array $grid = [];

    /**
     * @var array<Region>
     */
    public array $regions = [];

    /**
     * Hash map to keep track of processed plots
     *
     * @var array<string>
     */
    public array $processedPlots = [];

    public function __construct(string $inputFilepath)
    {
        $this->grid = $this->generateGrid(file($inputFilepath, FILE_IGNORE_NEW_LINES));
        $this->buildRegions();
    }

    public function addProcessedPlot(Plot $plot): void
    {
        $this->processedPlots[$plot->row.'-'.$plot->col] = $plot;
    }

    public function hasPlotBeenProcessed(Plot $plot): bool
    {
        return isset($this->processedPlots[$plot->row.'-'.$plot->col]);
    }

    private function buildRegions(): void
    {
        // loop the plots
        foreach ($this->grid as $rowKey => $row) {
            foreach ($row as $colKey => $plot) {
                // Once we hit a non-processed plot recursively traverse it
                if (! isset($this->processedPlots[$rowKey.'-'.$colKey])) {
                    $region = new Region($this->grid[$rowKey][$colKey], $this);
                    $region->traversePlots();
                    $this->regions[] = $region;
                }
            }
        }
    }

    private function generateGrid(array $input): array
    {
        $grid = [];
        foreach ($input as $rowKey => $line) {
            $grid[$rowKey] = [];
            $row = str_split($line);
            foreach ($row as $colKey => $col) {
                $node = new Plot($rowKey, $colKey, $col);
                $grid[$rowKey][$colKey] = $node;
            }
        }
        return $this->buildPlots($grid);
    }

    private function buildPlots(array $grid): array
    {
        foreach ($grid as $rowKey => $row) {
            foreach ($row as $colKey => $plot) {
                if (isset($grid[$rowKey - 1][$colKey])) {
                    $plot->north = $grid[$rowKey - 1][$colKey];
                }
                if (isset($row[$colKey + 1])) {
                    $plot->east = $row[$colKey + 1];
                }
                if (isset($grid[$rowKey + 1][$colKey])) {
                    $plot->south = $grid[$rowKey + 1][$colKey];
                }
                if (isset($row[$colKey - 1])) {
                    $plot->west = $row[$colKey - 1];
                }
            }
        }

        return $grid;
    }
}

class Region
{
    public Plot $top;
    public Farm $farm;
    /**
     * @var array<Plot>
     */
    public array $plots = [];
    public int $perimeter = 0;


    public function __construct(Plot $top, Farm $farm)
    {
        $this->top = $top;
        $this->farm = $farm;
    }

    public function getCost(): int
    {
        return count($this->plots) * $this->perimeter;
    }

    public function traversePlots(?Plot $plot = null): void
    {
        if ($plot === null) {
            $plot = $this->top;
        }

        if ( $this->farm->hasPlotBeenProcessed($plot)) {
            return;
        }

        $this->farm->addProcessedPlot($plot);

        $this->plots[] = $plot;



        if ($plot->north !== null && $plot->north->value === $plot->value) {
            self::traversePlots($plot->north);
        } else {
            $this->perimeter++;
        }

        if ($plot->east !== null && $plot->east->value === $plot->value) {
            self::traversePlots($plot->east);
        } else {
            $this->perimeter++;
        }

        if ($plot->south !== null && $plot->south->value === $plot->value) {
            self::traversePlots($plot->south);
        } else {
            $this->perimeter++;
        }

        if ($plot->west !== null && $plot->west->value === $plot->value) {
            self::traversePlots($plot->west);
        } else {
            $this->perimeter++;
        }

    }

}

class Plot
{
    public int $row;
    public int $col;
    public string $value;
    public ?Plot $north = null;
    public ?Plot $east = null;
    public ?Plot $south = null;
    public ?Plot $west = null;

    public function __construct(int $row, int $col, string $value)
    {
        $this->row = $row;
        $this->col = $col;
        $this->value = $value;
    }
}
