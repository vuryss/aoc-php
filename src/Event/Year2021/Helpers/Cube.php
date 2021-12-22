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
        if (
            $cube->xFrom > $this->xTo
            || $cube->xTo < $this->xFrom
            || $cube->yFrom > $this->yTo
            || $cube->yTo < $this->xFrom
            || $cube->zFrom > $this->zTo
            || $cube->zTo < $this->zFrom
        ) {
            return false;
        }

        return true;
    }

    public function getOverlapCube(Cube $cube): Cube
    {
        return new Cube(
            $cube->on,
            max($this->xFrom, $cube->xFrom),
            min($this->xTo, $cube->xTo),
            max($this->yFrom, $cube->yFrom),
            min($this->yTo, $cube->yTo),
            max($this->zFrom, $cube->zFrom),
            min($this->zTo, $cube->zTo),
        );
    }

    public function volume(): string
    {
        $x = bcadd(bcsub((string) $this->xTo, (string) $this->xFrom), '1');
        $y = bcadd(bcsub((string) $this->yTo, (string) $this->yFrom), '1');
        $z = bcadd(bcsub((string) $this->zTo, (string) $this->zFrom), '1');

        return bcmul(bcmul($x, $y), $z);
    }
}
