<?php

declare(strict_types=1);

namespace App\Util;

readonly class Range
{
    public function __construct(
        public int $start,
        public int $end,
    ) {
    }

    public function intersect(Range $range): ?Range
    {
        $start = max($this->start, $range->start);
        $end = min($this->end, $range->end);

        if ($start > $end) {
            return null;
        }

        return new Range($start, $end);
    }

    public function numberOfItems(): int
    {
        return $this->end - $this->start + 1;
    }
}
