<?php

declare(strict_types=1);

namespace App\Event\Year2021\Helpers;

class Amphipod
{
    public const TYPE_A = 'A';
    public const TYPE_B = 'B';
    public const TYPE_C = 'C';
    public const TYPE_D = 'D';

    public const TYPES = [
        self::TYPE_A => self::TYPE_A,
        self::TYPE_B => self::TYPE_B,
        self::TYPE_C => self::TYPE_C,
        self::TYPE_D => self::TYPE_D,
    ];

    public bool $inPosition = false;

    public function __construct(public string $type)
    {
    }
}
