<?php

declare(strict_types=1);

namespace App\Event\Year2023;

use App\Event\DayInterface;
use App\Util\StringUtil;

class Day9 implements DayInterface
{
    public function testPart1(): iterable
    {
        yield '114' => <<<'INPUT'
            0 3 6 9 12 15
            1 3 6 10 15 21
            10 13 16 21 30 45
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '2' => <<<'INPUT'
            0 3 6 9 12 15
            1 3 6 10 15 21
            10 13 16 21 30 45
            INPUT;
    }

    public function solvePart1(string $input): string|int
    {
        $lines = explode("\n", $input);
        $sum = 0;

        foreach ($lines as $line) {
            $sum += $this->extrapolateValue($line);
        }

        return $sum;
    }

    public function solvePart2(string $input): string|int
    {
        $lines = explode("\n", $input);
        $sum = 0;

        foreach ($lines as $line) {
            $sum += $this->extrapolateValue2($line);
        }

        return $sum;
    }

    private function extrapolateValue(string $line): int
    {
        $numbers = $this->buildSequences($line);
        $delta = 0;

        for ($i = count($numbers) - 1; $i >= 0; $i--) {
            $delta = $numbers[$i][count($numbers[$i]) - 1] + $delta;
            $numbers[$i][] = $delta;
        }

        return $delta;
    }

    private function extrapolateValue2(string $line): int
    {
        $numbers = $this->buildSequences($line);
        $delta = 0;

        for ($i = count($numbers) - 1; $i >= 0; $i--) {
            $delta = $numbers[$i][0] - $delta;
            $numbers[$i] = [$delta, ...$numbers[$i]];
        }

        return $delta;
    }

    private function buildSequences(string $line): array
    {
        $values = StringUtil::extractIntegers($line);
        $numbers[] = $values;
        $diffs = $values;

        while (count(array_unique($diffs)) > 1) {
            $values = $diffs;
            $diffs = [];

            for ($i = 0; $i < count($values) - 1; $i++) {
                $diffs[$i] = $values[$i + 1] - $values[$i];
            }

            $numbers[] = $diffs;
        }

        return $numbers;
    }
}
