<?php

declare(strict_types=1);

namespace App\Util;

readonly class StringUtil
{
    public static function extractIntegers(string $string): array
    {
        preg_match_all('/-?\d+/', $string, $matches);

        return array_map('intval', $matches[0]);
    }
}
