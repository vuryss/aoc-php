<?php

declare(strict_types=1);

namespace App\Event\Year2021;

use App\Event\DayInterface;

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
            $diff = $value['offset'] + $num1;

            if ($diff > 0) {
                $number[$index + 1] = 9;
                $number[$value['index'] + 1] = 9 - $diff;
            } else {
                $number[$value['index'] + 1] = 9;
                $number[$index + 1] = 9 + $diff;
            }
        }

        return implode('', $number);
    }

    public function solvePart2(string $input): string|int
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
            $diff = $value['offset'] + $num1;

            if ($diff > 0) {
                $number[$index + 1] = 1 + $diff;
                $number[$value['index'] + 1] = 1;
            } else {
                $number[$value['index'] + 1] = 1 - $diff;
                $number[$index + 1] = 1;
            }
        }

        return implode('', $number);
    }
}
