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

    public const MOVE_COST = [
        self::TYPE_A => 1,
        self::TYPE_B => 10,
        self::TYPE_C => 100,
        self::TYPE_D => 1000,
    ];
}
