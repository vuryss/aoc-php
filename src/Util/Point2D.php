<?php

declare(strict_types=1);

namespace App\Util;

use Ds\Hashable;

class Point2D implements Hashable
{
    public function __construct(
        public int $x,
        public int $y,
    ) {
    }

    public function surrounding(): array
    {
        return [
            new Point2D($this->x - 1, $this->y - 1),
            new Point2D($this->x, $this->y - 1),
            new Point2D($this->x + 1, $this->y - 1),
            new Point2D($this->x - 1, $this->y),
            new Point2D($this->x + 1, $this->y),
            new Point2D($this->x - 1, $this->y + 1),
            new Point2D($this->x, $this->y + 1),
            new Point2D($this->x + 1, $this->y + 1),
        ];
    }

    public function isSurrounding(Point2D $point): bool
    {
        foreach ($this->surrounding() as $surroundingPoint) {
            if ($point->x === $surroundingPoint->x && $point->y === $surroundingPoint->y) {
                return true;
            }
        }

        return false;
    }

    public function isHorizontallyAdjacent(Point2D $point): bool
    {
        return $this->y === $point->y && 1 === abs($this->x - $point->x);
    }

    public function clone(): Point2D
    {
        return new Point2D($this->x, $this->y);
    }

    public function equals(mixed $obj): bool
    {
        return $obj instanceof Point2D && $this->x === $obj->x && $this->y === $obj->y;
    }

    public function hash(): string
    {
        return 'Point2D('. $this->x . ',' . $this->y . ')';
    }
}
