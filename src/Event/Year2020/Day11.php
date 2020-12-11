<?php

declare(strict_types=1);

namespace App\Event\Year2020;

use App\Event\DayInterface;

class Day11 implements DayInterface
{
    public function testPart1(): iterable
    {
        yield '37' => <<<'INPUT'
            L.LL.LL.LL
            LLLLLLL.LL
            L.L.L..L..
            LLLL.LL.LL
            L.LL.LL.LL
            L.LLLLL.LL
            ..L.L.....
            LLLLLLLLLL
            L.LLLLLL.L
            L.LLLLL.LL
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '26' => <<<'INPUT'
            L.LL.LL.LL
            LLLLLLL.LL
            L.L.L..L..
            LLLL.LL.LL
            L.LL.LL.LL
            L.LLLLL.LL
            ..L.L.....
            LLLLLLLLLL
            L.LLLLLL.L
            L.LLLLL.LL
            INPUT;
    }

    public function solvePart1(string $input): string
    {
        $grid = array_map('str_split', explode("\n", $input));
        $newState = $grid;

        while (true) {
            foreach ($grid as $y => $line) {
                foreach ($line as $x => $spot) {
                    if ($spot === '.') continue;

                    $occupiedAround = 0;

                    for ($dy = -1; $dy <= 1; $dy++) {
                        for ($dx = -1; $dx <= 1; $dx++) {
                            if ($dy === 0 && $dx === 0) continue;
                            if (($grid[$y + $dy][$x + $dx] ?? '') === '#') $occupiedAround++;
                        }
                    }

                    if ($occupiedAround === 0) {
                        $newState[$y][$x] = '#';
                    } elseif ($occupiedAround >= 4) {
                        $newState[$y][$x] = 'L';
                    }
                }
            }

            if (serialize($newState) === serialize($grid)) {
                return (string) $this->countOccupiedSeats($grid);
            }

            $grid = $newState;
        }
    }

    public function solvePart2(string $input): string
    {
        $grid = array_map('str_split', explode("\n", $input));
        $newState = $grid;

        while (true) {
            foreach ($grid as $y => $line) {
                foreach ($line as $x => $spot) {
                    if ($spot === '.') continue;

                    $occupiedAround = 0;

                    for ($dy = -1; $dy <= 1; $dy++) {
                        for ($dx = -1; $dx <= 1; $dx++) {
                            if ($dy === 0 && $dx === 0) continue;

                            for ($dist = 1; isset($grid[$y + $dy * $dist][$x + $dx * $dist]); $dist++) {
                                if ($grid[$y + $dy * $dist][$x + $dx * $dist] === 'L') break;

                                if ($grid[$y + $dy * $dist][$x + $dx * $dist] === '#') {
                                    $occupiedAround++;
                                    break;
                                }
                            }
                        }
                    }

                    if ($occupiedAround === 0) {
                        $newState[$y][$x] = '#';
                    } elseif ($occupiedAround >= 5) {
                        $newState[$y][$x] = 'L';
                    }
                }
            }

            if (serialize($newState) === serialize($grid)) {
                return (string) $this->countOccupiedSeats($grid);
            }

            $grid = $newState;
        }
    }

    private function countOccupiedSeats(array $grid): int
    {
        $count = 0;

        foreach ($grid as $y => $line) {
            foreach ($line as $x => $spot) {
                if ($spot === '#') $count++;
            }
        }

        return $count;
    }
}
