<?php

declare(strict_types=1);

namespace App\Event;

abstract class Day implements DayInterface
{
    public function getDay(): int
    {
        return static::DAY;
    }

    public function getYear(): int
    {
        return static::YEAR;
    }
}
