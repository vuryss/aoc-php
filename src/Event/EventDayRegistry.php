<?php

declare(strict_types=1);

namespace App\Event;

class EventDayRegistry
{
    private array $daysByYear;

    public function __construct()
    {
        $this->daysByYear = [];
    }

    public function addDay(DayInterface $day): self
    {
        $this->daysByYear[$day->getYear()][$day->getDay()] = $day;

        return $this;
    }

    public function getDayInYear(int $year, int $day): ?DayInterface
    {
        return $this->daysByYear[$year][$day] ?? null;
    }
}
