<?php

declare(strict_types=1);

namespace App\Event\Year2021\Helpers;

class Point3
{
    public function __construct(
        public int $x = 0,
        public int $y = 0,
        public int $z = 0,
    ) {
    }

    public function equalsTo(Point3 $point): bool
    {
        return $this->x === $point->x && $this->y === $point->y && $this->z === $point->z;
    }
}
