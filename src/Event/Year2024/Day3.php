<?php

declare(strict_types=1);

namespace App\Event\Year2024;

use App\Event\DayInterface;

class Day3 implements DayInterface
{
    public function testPart1(): iterable
    {
        yield '161' => <<<'INPUT'
            xmul(2,4)%&mul[3,7]!@^do_not_mul(5,5)+mul(32,64]then(mul(11,8)mul(8,5))
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '48' => <<<'INPUT'
            xmul(2,4)&mul[3,7]!^don't()_mul(5,5)+mul(32,64](mul(11,8)undo()?mul(8,5))
            INPUT;
    }

    public function solvePart1(string $input): string|int
    {
        preg_match_all('/mul\((\d+),(\d+)\)/', $input, $matches);

        return array_reduce(array_map(null, ...$matches), fn ($carry, $item) => $carry + ($item[1] * $item[2]), 0);
    }

    public function solvePart2(string $input): string|int
    {
        preg_match_all('/do\(\)|don\'t\(\)|mul\((\d+),(\d+)\)/', $input, $matches);
        $sum = 0;
        $enabled = true;

        foreach (array_map(null, ...$matches) as $match) {
            if ($match[0] === 'do()') {
                $enabled = true;
            } elseif ($match[0] === 'don\'t()') {
                $enabled = false;
            } elseif ($enabled) {
                $sum += $match[1] * $match[2];
            }
        }

        return $sum;
    }
}
