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
        $lines = explode("\n", strtr($input, 'FBLR', '0101'));
        rsort($lines);
        return base_convert($lines[0], 2, 10);
    }

    public function solvePart2(string $input): string
    {
        $lines = explode("\n", strtr($input, 'FBLR', '0101'));
        $lines = array_map(fn ($a) => (int) base_convert($a, 2, 10), $lines);

        for ($num = min($lines), $max = max($lines), $lines = array_flip($lines); $num < $max; $num++) {
            if (!isset($lines[$num])) {
                return (string) $num;
            }
        }

        throw new Exception('Result not found');
    }
}
