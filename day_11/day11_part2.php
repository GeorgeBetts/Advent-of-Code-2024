<?php

/**
 * Stones don't need to be in a list - the order doesn't matter
 *
 * Take each stone and recursively 'blink' it returning the stone count at the end of it's blinks
 */

include __DIR__.'/../PuzzleTimer.php';

$timer = new PuzzleTimer();
$input = file_get_contents(__DIR__.'/input.txt');

$solutions = [];
$stoneCount = 0;
$blinkTimes = 75;
foreach (explode(' ', $input) as $item) {
    $stone = new Stone(intval($item));
    $stoneCount += $stone->countStoneTotalsAfterBlinking($blinkTimes, $solutions);
}


echo $stoneCount;
$timer->output();

class Stone
{
    public int $value;

    /**
     * @param int $value
     */
    public function __construct(int $value)
    {
        $this->value = $value;
    }

    public function countStoneTotalsAfterBlinking(int $remainingBlinkCount = 1, array &$solutions = [], ?Stone $stone = null): int
    {
        if ($stone === null) {
            $stone = $this;
        }

        if (isset($solutions[$key = $stone->value.'-'.$remainingBlinkCount])) return $solutions[$key];

        if ($remainingBlinkCount === 0) {
            $stoneCount = 1;
        } else {
            // blink the stone
            $blinkResult = $stone->blink();

            if (count($blinkResult) === 2) {
                $stoneCount = $blinkResult[0]->countStoneTotalsAfterBlinking($remainingBlinkCount - 1, $solutions) + $blinkResult[1]->countStoneTotalsAfterBlinking($remainingBlinkCount - 1, $solutions);
            } else {
                $stoneCount = $blinkResult[0]->countStoneTotalsAfterBlinking($remainingBlinkCount - 1, $solutions);
            }
        }


        $solutions[$key] = $stoneCount;
        return $stoneCount;
    }

    /**
     * @return array<Stone>
     */
    public function blink(): array
    {
        if ($this->isZero()) {
            return [new Stone(1)];
        }
        if ($this->hasEvenDigits()) {
            $values = $this->getSplitValues();
            // add new stones
            return [new Stone($values[0]), new Stone($values[1])];
        }
        return [new Stone($this->value * 2024)];
    }

    private function isZero(): bool
    {
        return $this->value === 0;
    }

    private function hasEvenDigits(): bool
    {
        return $this->getDigitCount() % 2 === 0;
    }

    private function getSplitValues(): array
    {
        return str_split((string) $this->value, $this->getDigitCount() / 2);
    }

    private function getDigitCount(): int
    {
        return $this->value !== 0 ? floor(log10($this->value) + 1) : 1;
    }

}
