<?php

declare(strict_types=1);

namespace App;

readonly class PuzzleId
{
    public function __construct(
        public int $event,
        public ?int $day = null,
    ) {
        if ($event < 2015) {
            throw new \InvalidArgumentException('There are no AoC events before 2015');
        }

        $currentMonth = (int) date('n');
        $currentYear = (int) date('Y');

        if ($event > $currentYear) {
            throw new \InvalidArgumentException('No puzzle available for year ' . $event);
        }

        if ($currentMonth < 12 && $event === $currentYear) {
            throw new \InvalidArgumentException('The event has not started yet for this year');
        }

        if (null === $day) {
            return;
        }

        if ($day < 1 || $day > 25) {
            throw new \InvalidArgumentException('Invalid day number');
        }
    }
}
