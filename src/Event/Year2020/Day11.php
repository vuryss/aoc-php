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

                    if (($grid[$y - 1][$x - 1] ?? '') === '#') $occupiedAround++;
                    if (($grid[$y - 1][$x] ?? '') === '#') $occupiedAround++;
                    if (($grid[$y - 1][$x + 1] ?? '') === '#') $occupiedAround++;
                    if (($grid[$y][$x - 1] ?? '') === '#') $occupiedAround++;
                    if (($grid[$y][$x + 1] ?? '') === '#') $occupiedAround++;
                    if (($grid[$y + 1][$x - 1] ?? '') === '#') $occupiedAround++;
                    if (($grid[$y + 1][$x] ?? '') === '#') $occupiedAround++;
                    if (($grid[$y + 1][$x + 1] ?? '') === '#') $occupiedAround++;

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

                    for ($i = 1; isset($grid[$y - $i][$x - $i]); $i++) {
                        if ($grid[$y - $i][$x - $i] === '#') {
                            $occupiedAround++;
                            break;
                        } elseif ($grid[$y - $i][$x - $i] === 'L') {
                            break;
                        }
                    }

                    for ($i = 1; isset($grid[$y - $i][$x]); $i++) {
                        if ($grid[$y - $i][$x] === '#') {
                            $occupiedAround++;
                            break;
                        } elseif ($grid[$y - $i][$x] === 'L') {
                            break;
                        }
                    }

                    for ($i = 1; isset($grid[$y - $i][$x + $i]); $i++) {
                        if ($grid[$y - $i][$x + $i] === '#') {
                            $occupiedAround++;
                            break;
                        } elseif ($grid[$y - $i][$x + $i] === 'L') {
                            break;
                        }
                    }

                    for ($i = 1; isset($grid[$y][$x - $i]); $i++) {
                        if ($grid[$y][$x - $i] === '#') {
                            $occupiedAround++;
                            break;
                        } elseif ($grid[$y][$x - $i] === 'L') {
                            break;
                        }
                    }

                    for ($i = 1; isset($grid[$y][$x + $i]); $i++) {
                        if ($grid[$y][$x + $i] === '#') {
                            $occupiedAround++;
                            break;
                        } elseif ($grid[$y][$x + $i] === 'L') {
                            break;
                        }
                    }

                    for ($i = 1; isset($grid[$y + $i][$x - $i]); $i++) {
                        if ($grid[$y + $i][$x - $i] === '#') {
                            $occupiedAround++;
                            break;
                        } elseif ($grid[$y + $i][$x - $i] === 'L') {
                            break;
                        }
                    }

                    for ($i = 1; isset($grid[$y + $i][$x]); $i++) {
                        if ($grid[$y + $i][$x] === '#') {
                            $occupiedAround++;
                            break;
                        } elseif ($grid[$y + $i][$x] === 'L') {
                            break;
                        }
                    }

                    for ($i = 1; isset($grid[$y + $i][$x + $i]); $i++) {
                        if ($grid[$y + $i][$x + $i] === '#') {
                            $occupiedAround++;
                            break;
                        } elseif ($grid[$y + $i][$x + $i] === 'L') {
                            break;
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
