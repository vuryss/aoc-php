<?php

declare(strict_types=1);

namespace App\Event\Year2020;

use App\Event\DayInterface;

class Day13 implements DayInterface
{
    public function testPart1(): iterable
    {
        yield '295' => <<<'INPUT'
            939
            7,13,x,x,59,x,31,19
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '1068781' => <<<'INPUT'
            939
            7,13,x,x,59,x,31,19
            INPUT;

        yield '3417' => <<<'INPUT'
            0
            17,x,13,19
            INPUT;

        yield '754018' => <<<'INPUT'
            0
            67,7,59,61
            INPUT;

        yield '779210' => <<<'INPUT'
            0
            67,x,7,59,61
            INPUT;

        yield '1261476' => <<<'INPUT'
            0
            67,7,x,59,61
            INPUT;

        yield '1202161486' => <<<'INPUT'
            0
            1789,37,47,1889
            INPUT;
    }

    public function solvePart1(string $input): string
    {
        [$estimateMinutes, $buses] = explode("\n", $input);
        $buses = array_map('intval', array_filter(explode(',', $buses), fn ($bus) => $bus !== 'x'));

        $minMinutes = PHP_INT_MAX;
        $busId = 0;

        foreach ($buses as $bus) {
            $diff = $bus - ($estimateMinutes % $bus);

            if ($diff < $minMinutes) {
                $minMinutes = $diff;
                $busId = $bus;
            }
        }

        return (string) ($busId * $minMinutes);
    }

    /**
     * Search for Chinese Remainder Theorem to understand this.
     *
     * @param string $input
     *
     * @return string
     */
    public function solvePart2(string $input): string
    {
        [, $buses] = explode("\n", $input);
        $busOffsets = [];

        foreach (explode(',', $buses) as $offset => $bus) {
            if ($bus === 'x') continue;
            $busOffsets[$bus] = $offset > 0 ? $bus - $offset : 0;
        }

        $product = array_product(array_keys($busOffsets));
        $sum = 0;

        foreach ($busOffsets as $bus => $offset) {
            $m = $product / $bus;
            $invM = gmp_intval(gmp_invert($m, $bus));
            $sum += $offset * $m * $invM;
        }

        return (string) ($sum % $product);
    }
}
