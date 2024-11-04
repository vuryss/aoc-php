<?php

declare(strict_types=1);

namespace App\Util;

readonly class NumberUtil
{
    public static function isBetween(int $value, int $value1, int $value2): bool
    {
        return min($value1, $value2) <= $value && $value <= max($value1, $value2);
    }
}
