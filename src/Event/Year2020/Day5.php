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
        return base_convert(max(explode("\n", strtr($input, 'FBLR', '0101'))), 2, 10);
    }

    public function solvePart2(string $input): string
    {
        $lines = explode("\n", strtr($input, 'FBLR', '0101'));
        $lines = array_map(fn ($a) => (int) base_convert($a, 2, 10), $lines);
        return (string) current(array_filter(range(min($lines), max($lines)), fn ($a) => !in_array($a, $lines)));
    }
}
