<?php

declare(strict_types=1);

namespace App\Event\Year2021;

use App\Event\DayInterface;
use Closure;

class Day24 implements DayInterface
{
    public function testPart1(): iterable
    {
        return [];
    }

    public function testPart2(): iterable
    {
        return [];
    }

    public function solvePart1(string $input): string|int
    {
        return $this->solve($input, Closure::fromCallable([$this, 'maximize']));
    }

    public function solvePart2(string $input): string|int
    {
        return $this->solve($input, Closure::fromCallable([$this, 'minimize']));
    }

    private function solve(string $input, callable $diffFn): string
    {
        $lines = explode("\n", $input);
        $parts = array_chunk($lines, 18);

        $stack = [];
        $number = array_fill_keys(range(1, 14), 0);

        foreach ($parts as $index => $part) {
            $num1 = (int) explode(' ', $part[5])[2];
            $num2 = (int) explode(' ', $part[15])[2];

            if ($num1 > 9) {
                $stack[] = ['index' => $index, 'offset' => $num2];
                continue;
            }

            $value = array_pop($stack);
            $result = $diffFn($value['offset'] + $num1);

            $number[$index + 1] = $result[0];
            $number[$value['index'] + 1] = $result[1];
        }

        return implode('', $number);
    }

    private function maximize(int $diff): array
    {
        if ($diff > 0) {
            return [9, 9 - $diff];
        }

        return [9 + $diff, 9];
    }

    private function minimize(int $diff): array
    {
        if ($diff > 0) {
            return [1 + $diff, 1];
        }

        return [1, 1 - $diff];
    }
}
