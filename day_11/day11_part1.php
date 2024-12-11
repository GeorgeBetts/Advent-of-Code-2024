<?php

include __DIR__.'/../PuzzleTimer.php';

$timer = new PuzzleTimer();
$input = file_get_contents(__DIR__.'/input.txt');
$stones = new StoneList();
foreach (explode(' ', $input) as $item) {
    $stones->push(new Stone(intval($item)));
}


for ($i = 0; $i < 25; $i++) {
    $stones->blink();
}

echo $stones->count();
$timer->output();

class StoneList extends SplDoublyLinkedList
{
    public function blink(): void
    {
        for ($this->rewind(); $this->valid(); $this->next()) {
            if (! $this->current() instanceof Stone) continue;
            if ($this->current()->isZero()) {
                $this->current()->value = 1;
                continue;
            }
            if ($this->current()->hasEvenDigits()) {
                $values = $this->current()->getSplitValues();
                // overwrite the left stone
                $this->current()->value = $values[0];
                // add a new right stone
                $this->add($this->key() + 1, new Stone($values[1]));
                $this->next();
                continue;
            }
            $this->current()->value = $this->current()->value * 2024;
        }
    }
}

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

    public function isZero(): bool
    {
        return $this->value === 0;
    }

    public function hasEvenDigits(): bool
    {
        return $this->getDigitCount() % 2 === 0;
    }

    public function getSplitValues(): array
    {
        return str_split((string) $this->value, $this->getDigitCount() / 2);
    }


    private function getDigitCount(): int
    {
        return $this->value !== 0 ? floor(log10($this->value) + 1) : 1;
    }

}
