<?php

declare(strict_types=1);

namespace App\Event\Year2021;

use App\Event\DayInterface;

class Day1 implements DayInterface
{
    public function testPart1(): iterable
    {
        yield '7' => <<<'INPUT'
            199
            200
            208
            210
            200
            207
            240
            269
            260
            263
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '5' => <<<'INPUT'
            199
            200
            208
            210
            200
            207
            240
            269
            260
            263
            INPUT;
    }

    public function solvePart1(string $input): string
    {
        $depths = array_map('intval', explode("\n", $input));
        $count = array_reduce(
            $depths,
            static fn ($carry, $item): array => [$carry[0] + (int) ($item > $carry[1]), $item],
            [0, array_shift($depths)]
        );

        return (string) $count[0];
    }

    public function solvePart2(string $input): string
    {
        $depths = array_map('intval', explode("\n", $input));
        $previous = array_splice($depths, 0, 3);
        $count = 0;

        while ($item = array_shift($depths)) {
            $window = [$previous[1], $previous[2], $item];
            $count += (int) (array_sum($window) > array_sum($previous));
            $previous = $window;
        }

        return (string) $count;
    }
}
