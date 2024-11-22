<?php

declare(strict_types=1);

namespace App\Event\Year2023\Day19;

readonly class Rule
{
    public function __construct(
        public string $destination,
        public ?Condition $condition = null,
    ) {
    }
}
