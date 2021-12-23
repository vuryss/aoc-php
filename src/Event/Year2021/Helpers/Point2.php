<?php

declare(strict_types=1);

namespace App\Event\Year2021\Helpers;

class Point2
{
    public function __construct(
        public int $x,
        public int $y,
    ) {
    }

    public function equals(Point2 $point): bool
    {
        return $point->x === $this->x && $point->y === $this->y;
    }
}
