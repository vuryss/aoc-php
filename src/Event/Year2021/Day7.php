<?php

declare(strict_types=1);

namespace App\Event\Year2021;

use App\Event\DayInterface;

class Day7 implements DayInterface
{
    public function testPart1(): iterable
    {
        yield '37' => <<<'INPUT'
            16,1,2,0,4,2,7,1,2,14
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '168' => <<<'INPUT'
            16,1,2,0,4,2,7,1,2,14
            INPUT;
    }

    public function solvePart1(string $input): string
    {
        $positions = array_map('intval', explode(',', $input));
        $fuel = PHP_INT_MAX;

        for ($i = min($positions), $max = max($positions); $i <= $max; $i++) {
            $sum = 0;

            foreach ($positions as $position) {
                $sum += abs($position - $i);
            }

            $fuel = min($fuel, $sum);
        }

        return (string) $fuel;
    }

    public function solvePart2(string $input): string
    {
        $positions = array_map('intval', explode(',', $input));
        $fuel = PHP_INT_MAX;

        for ($i = min($positions), $max = max($positions); $i <= $max; $i++) {
            $sum = 0;

            foreach ($positions as $position) {
                $steps = abs($position - $i);
                $sum += $steps * ($steps + 1) / 2;
            }

            $fuel = min($fuel, $sum);
        }

        return (string) $fuel;
    }
}
