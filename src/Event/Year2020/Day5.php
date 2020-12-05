<?php

declare(strict_types=1);

namespace App\Event\Year2020;

use App\Event\Day;
use Exception;

class Day5 extends Day
{
    const YEAR = 2020;
    const DAY = 5;

    public function testPart1(): iterable
    {
        yield '357' => 'FBFBBFFRLR';
        yield '567' => 'BFFFBBFRRR';
        yield '119' => 'FFFBBBFRRR';
        yield '820' => 'BBFFBBFRLL';
    }

    public function testPart2(): iterable
    {
        return [];
    }

    public function solvePart1(string $input): string
    {
        $lines = explode("\n", $input);
        $max = PHP_INT_MIN;

        foreach ($lines as $code) {
            $code = strtr($code, 'FBLR', '0101');
            $num = (int) base_convert($code, 2, 10);
            $max = $num > $max ? $num : $max;
        }

        return (string) $max;
    }

    public function solvePart2(string $input): string
    {
        $lines = explode("\n", $input);
        $max = PHP_INT_MIN;
        $min = PHP_INT_MAX;
        $map = [];

        foreach ($lines as $code) {
            $code = strtr($code, 'FBLR', '0101');
            $num = (int) base_convert($code, 2, 10);
            $max = $num > $max ? $num : $max;
            $min = $num < $min ? $num : $min;
            $map[$num] = true;
        }

        for ($i = $min; $i < $max; $i++) {
            if (!isset($map[$i])) {
                return (string) $i;
            }
        }

        throw new Exception('Result not found');
    }
}
