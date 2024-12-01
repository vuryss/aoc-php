<?php

declare(strict_types=1);

namespace App\Event\Year2024;

use App\Event\DayInterface;
use App\Util\StringUtil;

class Day1 implements DayInterface
{
    public function testPart1(): iterable
    {
        yield '11' => <<<'INPUT'
            3   4
            4   3
            2   5
            1   3
            3   9
            3   3
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '31' => <<<'INPUT'
            3   4
            4   3
            2   5
            1   3
            3   9
            3   3
            INPUT;
    }

    public function solvePart1(string $input): string|int
    {
        $lines = array_map(StringUtil::extractIntegers(...), explode("\n", $input));
        $col1 = array_column($lines, 0);
        $col2 = array_column($lines, 1);
        $sum = 0;
        sort($col1);
        sort($col2);

        for ($i = 0; $i < count($col1); $i++) {
            $sum += abs($col2[$i] - $col1[$i]);
        }

        return (int) $sum;
    }

    public function solvePart2(string $input): string|int
    {
        $lines = array_map(StringUtil::extractIntegers(...), explode("\n", $input));
        $col1 = array_column($lines, 0);
        $col2 = array_count_values(array_column($lines, 1));
        $sum = 0;

        foreach ($col1 as $num1) {
            $sum += $num1 * ($col2[$num1] ?? 0);
        }

        return $sum;
    }
}
