<?php

declare(strict_types=1);

namespace App\Event\Year2021\Helpers;

class Octopus
{
    /** @var Octopus[] */
    public array $adjacent = [];
    private bool $hasFlashed = false;

    public function __construct(public int $energy)
    {
    }

    public function energize(): int
    {
        if (++$this->energy > 9 && !$this->hasFlashed) {
            return 1 + $this->flash();
        }

        return 0;
    }

    private function flash(): int
    {
        $this->hasFlashed = true;
        $counter = 0;

        foreach ($this->adjacent as $octopus) {
            $counter += $octopus->energize();
        }

        return $counter;
    }

    public function rest(): void
    {
        if ($this->hasFlashed) {
            $this->energy = 0;
            $this->hasFlashed = false;
        }
    }
}
