<?php

declare(strict_types=1);

namespace App\Event\Year2024;

use App\Event\DayInterface;
use App\Util\StringUtil;

class Day7 implements DayInterface
{
    public function testPart1(): iterable
    {
        yield '3749' => <<<'INPUT'
            190: 10 19
            3267: 81 40 27
            83: 17 5
            156: 15 6
            7290: 6 8 6 15
            161011: 16 10 13
            192: 17 8 14
            21037: 9 7 18 13
            292: 11 6 16 20
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '11387' => <<<'INPUT'
            190: 10 19
            3267: 81 40 27
            83: 17 5
            156: 15 6
            7290: 6 8 6 15
            161011: 16 10 13
            192: 17 8 14
            21037: 9 7 18 13
            292: 11 6 16 20
            INPUT;
    }

    public function solvePart1(string $input): string|int
    {
        $list = array_map(StringUtil::extractIntegers(...), explode("\n", $input));
        $sum = 0;

        foreach ($list as $numbers) {
            $result = array_shift($numbers);

            foreach ($this->p1($numbers, $result) as $value) {
                if ($value === $result) {
                    $sum += $result;
                    break;
                }
            }
        }

        return $sum;
    }

    public function solvePart2(string $input): string|int
    {
        $list = array_map(StringUtil::extractIntegers(...), explode("\n", $input));
        $sum = 0;

        foreach ($list as $numbers) {
            $result = array_shift($numbers);

            foreach ($this->p2($numbers, $result) as $value) {
                if ($value === $result) {
                    $sum += $result;
                    break;
                }
            }
        }

        return $sum;
    }

    private function p1(array $numbers, int $max): iterable
    {
        $num1 = array_pop($numbers);

        foreach (count($numbers) === 1 ? $numbers : $this->p1($numbers, $max) as $num2) {
            if ($num2 <= $max) {
                yield $num1 + $num2;
                yield $num1 * $num2;
            }
        }
    }

    private function p2(array $numbers, int $max): iterable
    {
        $num1 = array_pop($numbers);

        foreach (count($numbers) === 1 ? $numbers : $this->p2($numbers, $max) as $num2) {
            if ($num2 <= $max) {
                yield (int) ($num2 . $num1);
                yield $num1 + $num2;
                yield $num1 * $num2;
            }
        }
    }
}
