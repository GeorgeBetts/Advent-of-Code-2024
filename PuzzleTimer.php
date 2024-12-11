<?php

class PuzzleTimer
{
    private float $startTime;

    public function __construct()
    {
        $this->startTime = microtime(true);
    }


    public function output(): void
    {
        echo PHP_EOL."Execution time: ".round(microtime(true) - $this->startTime, 4)." seconds".PHP_EOL;
        echo "Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB".PHP_EOL;
    }
}
