<?php

declare(strict_types=1);

namespace App\Event\Year2023;

use App\Event\DayInterface;
use App\Event\Year2023\Day14\Platform;

class Day14 implements DayInterface
{
    public function testPart1(): iterable
    {
        yield '136' => <<<'INPUT'
            O....#....
            O.OO#....#
            .....##...
            OO.#O....O
            .O.....O#.
            O.#..O.#.#
            ..O..#O..O
            .......O..
            #....###..
            #OO..#....
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '64' => <<<'INPUT'
            O....#....
            O.OO#....#
            .....##...
            OO.#O....O
            .O.....O#.
            O.#..O.#.#
            ..O..#O..O
            .......O..
            #....###..
            #OO..#....
            INPUT;
    }

    public function solvePart1(string $input): string|int
    {
        $platform = new Platform($input);
        $platform->tiltNorth();

        return $platform->score();
    }

    public function solvePart2(string $input): string|int
    {
        $platform = new Platform($input);
        $hashStore = [$platform->hash() => 0];
        $foundCycle = false;

        for ($i = 0; $i < 1000000000; $i++) {
            $platform->tiltNorth();
            $platform->tiltWest();
            $platform->tiltSouth();
            $platform->tiltEast();

            $hash = $platform->hash();

            if ($foundCycle) {
                continue;
            }

            if (isset($hashStore[$hash])) {
                $cycle = $i - $hashStore[$hash] + 1;
                $remaining = 1000000000 - $i;
                $cycles = floor($remaining / $cycle);
                $i += $cycles * $cycle;
                $foundCycle = true;
            } else {
                $hashStore[$hash] = $i + 1;
            }
        }

        return $platform->score();
    }
}
