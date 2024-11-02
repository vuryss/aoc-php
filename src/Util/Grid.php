<?php

declare(strict_types=1);

namespace App\Util;

/**
 * @template T
 */
class Grid
{
    /**
     * @param array<integer, array<integer, T>> $grid
     */
    public function __construct(
        public array $grid = [],
    ) {
    }

    /**
     * @param T $value
     */
    public function set(Point2D $point, mixed $value): self
    {
        $this->grid[$point->y][$point->x] = $value;

        return $this;
    }

    /**
     * @return T|null
     */
    public function get(Point2D $point): mixed
    {
        return $this->grid[$point->y][$point->x] ?? null;
    }

    public function totalNodes(): int
    {
        return count($this->grid, COUNT_RECURSIVE) - count($this->grid);
    }
}
