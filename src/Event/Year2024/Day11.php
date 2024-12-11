<?php

declare(strict_types=1);

namespace App\Event\Year2024;

use App\Event\DayInterface;
use App\Util\StringUtil;

class Day11 implements DayInterface
{
    private array $cache = [];

    public function testPart1(): iterable
    {
        yield '55312' => <<<'INPUT'
            125 17
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '65601038650482' => <<<'INPUT'
            125 17
            INPUT;
    }

    public function solvePart1(string $input): string|int
    {
        $numbers = StringUtil::extractIntegers($input);
        $count = 0;

        foreach ($numbers as $number) {
            $count += $this->solve($number, 25);
        }

        return $count;
    }

    public function solvePart2(string $input): string|int
    {
        $numbers = StringUtil::extractIntegers($input);
        $count = 0;

        foreach ($numbers as $number) {
            $count += $this->solve($number, 75);
        }

        return $count;
    }

    private function solve(int $number, int $iterations, int $level = 1) {
        if ($iterations === 0) {
            return 1;
        }

        if (isset($this->cache[$number][$iterations])) {
            return $this->cache[$number][$iterations];
        }

        if ($number === 0) {
            return $this->cache[$number][$iterations] = $this->solve(1, $iterations - 1, $level + 1);
        }

        if (strlen((string) $number) % 2 === 0) {
            [$num1, $num2] = str_split((string) $number, strlen((string) $number) / 2);
            return $this->cache[$number][$iterations] = $this->solve((int) $num1, $iterations - 1, $level + 1) + $this->solve((int) $num2, $iterations - 1, $level + 1);
        }

        return $this->cache[$number][$iterations] = $this->solve($number * 2024, $iterations - 1, $level + 1);
    }
}
