<?php

declare(strict_types=1);

namespace App\Event\Year2021;

use App\Event\DayInterface;
use App\Event\Year2021\Helpers\Octopus;

class Day11 implements DayInterface
{
    private const ADJACENT_DELTAS = [
        [-1, -1],
        [-1, 0],
        [-1, 1],
        [0, -1],
        [0, 1],
        [1, -1],
        [1, 0],
        [1, 1],
    ];

    public function testPart1(): iterable
    {
        yield '1656' => <<<'INPUT'
            5483143223
            2745854711
            5264556173
            6141336146
            6357385478
            4167524645
            2176841721
            6882881134
            4846848554
            5283751526
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '195' => <<<'INPUT'
            5483143223
            2745854711
            5264556173
            6141336146
            6357385478
            4167524645
            2176841721
            6882881134
            4846848554
            5283751526
            INPUT;
    }

    public function solvePart1(string $input): string
    {
        $octopuses = $this->generateOctopuses($input);

        $flashCounter = 0;

        for ($step = 1; $step <= 100; $step++) {
            foreach ($octopuses as $octopus) {
                $flashCounter += $octopus->energize();
            }

            foreach ($octopuses as $octopus) {
                $octopus->rest();
            }
        }

        return (string) $flashCounter;
    }

    public function solvePart2(string $input): string
    {
        $octopuses = $this->generateOctopuses($input);
        $total = count($octopuses);

        $step = 0;
        do {
            $stepFlashCounter = 0;
            $step++;

            foreach ($octopuses as $octopus) {
                $stepFlashCounter += $octopus->energize();
            }

            foreach ($octopuses as $octopus) {
                $octopus->rest();
            }
        } while ($stepFlashCounter !== $total);

        return (string) $step;
    }

    /**
     * @return Octopus[]
     */
    private function generateOctopuses(string $input): array
    {
        $lines = explode("\n", $input);
        $grid = $all = [];

        foreach ($lines as $y => $line) {
            foreach (str_split($line) as $x => $energy) {
                $all[] = $grid[$y][$x] = new Octopus((int) $energy);
            }
        }

        foreach ($grid as $y => $line) {
            foreach ($line as $x => $octopus) {
                foreach (self::ADJACENT_DELTAS as [$dy, $dx]) {
                    if (isset($grid[$y + $dy][$x + $dx])) {
                        $octopus->adjacent[] = $grid[$y + $dy][$x + $dx];
                    }
                }
            }
        }

        return $all;
    }
}
