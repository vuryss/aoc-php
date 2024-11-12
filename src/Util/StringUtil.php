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

    public static function inputToGridOfChars(string $string): array
    {
        $grid = [];
        $y = 0;

        foreach (explode("\n", $string) as $line) {
            $grid[$y++] = str_split($line);
        }

        return $grid;
    }
}
