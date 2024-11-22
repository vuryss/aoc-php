<?php

declare(strict_types=1);

namespace App\Event\Year2023\Day19;

readonly class Condition
{
    public function __construct(
        public string $category,
        public Operator $operator,
        public int $value,
    ) {
    }
}
