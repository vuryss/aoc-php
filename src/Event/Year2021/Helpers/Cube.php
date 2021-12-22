<?php

declare(strict_types=1);

namespace App\Event\Year2021\Helpers;

class Cube
{
    public function __construct(
        public bool $on,
        public int $xFrom,
        public int $xTo,
        public int $yFrom,
        public int $yTo,
        public int $zFrom,
        public int $zTo,
    ) {
    }

    public function overlaps(Cube $cube): bool
    {
        return !($cube->xFrom > $this->xTo
            || $cube->xTo < $this->xFrom
            || $cube->yFrom > $this->yTo
            || $cube->yTo < $this->yFrom
            || $cube->zFrom > $this->zTo
            || $cube->zTo < $this->zFrom);
    }

    public function generateOverlapCube(Cube $cube): Cube
    {
        return new Cube(
            !$cube->on,
            max($this->xFrom, $cube->xFrom),
            min($this->xTo, $cube->xTo),
            max($this->yFrom, $cube->yFrom),
            min($this->yTo, $cube->yTo),
            max($this->zFrom, $cube->zFrom),
            min($this->zTo, $cube->zTo),
        );
    }

    public function volume(): int
    {
        return ($this->xTo - $this->xFrom + 1) * ($this->yTo - $this->yFrom + 1) * ($this->zTo - $this->zFrom + 1);
    }
}
