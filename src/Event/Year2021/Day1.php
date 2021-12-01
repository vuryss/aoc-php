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
        $previous = array_shift($depths);
        $count = 0;

        foreach ($depths as $depth) {
            if ($depth > $previous) {
                $count++;
            }
            $previous = $depth;
        }

        return (string) $count;
    }

    public function solvePart2(string $input): string
    {
        $depths = array_map('intval', explode("\n", $input));
        $count = 0;

        for ($i = 0, $end = count($depths) - 3; $i < $end; $i++) {
            $window1 = $depths[$i] + $depths[$i + 1] + $depths[$i + 2];
            $window2 = $depths[$i + 1] + $depths[$i + 2] + $depths[$i + 3];

            if ($window2 > $window1) {
                $count++;
            }
        }

        return (string) $count;
    }
}
