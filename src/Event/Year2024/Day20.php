<?php

declare(strict_types=1);

namespace App\Event\Year2024;

use App\Event\DayInterface;
use App\Util\Point2D;
use Ds\Queue;

class Day20 implements DayInterface
{
    // X, Y
    private const array DELTA = [[0, -2], [2, 0], [0, 2], [-2, 0]];

    public function testPart1(): iterable
    {
        yield '4' => <<<'INPUT'
            ###############
            #...#...#.....#
            #.#.#.#.#.###.#
            #S#...#.#.#...#
            #######.#.#.###
            #######.#.#...#
            #######.#.###.#
            ###..E#...#...#
            ###.#######.###
            #...###...#...#
            #.#####.#.###.#
            #.#...#.#.#...#
            #.#.#.#.#.#.###
            #...#...#...###
            ###############
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '881' => <<<'INPUT'
            ###############
            #...#...#.....#
            #.#.#.#.#.###.#
            #S#...#.#.#...#
            #######.#.#.###
            #######.#.#...#
            #######.#.###.#
            ###..E#...#...#
            ###.#######.###
            #...###...#...#
            #.#####.#.###.#
            #.#...#.#.#...#
            #.#.#.#.#.#.###
            #...#...#...###
            ###############
            INPUT;
    }

    public function solvePart1(string $input): string|int
    {
        $tileSteps = $this->parsePath($input);
        $targetSteps = count($tileSteps) > 20 ? 100 : 30;
        $count = 0;

        foreach ($tileSteps as $y => $line) {
            foreach ($line as $x => $tileTime) {

                foreach (self::DELTA as $d) {
                    [$nX, $nY] = [$x + $d[0], $y + $d[1]];

                    if (isset($tileSteps[$nY][$nX]) && $tileSteps[$nY][$nX] > $tileTime) {
                        if ($tileSteps[$nY][$nX] - $tileTime - 2 >= $targetSteps) {
                            $count++;
                        }
                    }
                }
            }
        }

        return $count;
    }

    public function solvePart2(string $input): string|int
    {
        $tileSteps = $this->parsePath($input);
        $targetSteps = count($tileSteps) > 20 ? 100 : 30;
        $count = 0;

        foreach ($tileSteps as $y => $line) {
            foreach ($line as $x => $tileTime) {
                foreach ($tileSteps as $tY => $tLine) {
                    foreach ($tLine as $tX => $tTileTime) {
                        if ($tTileTime >= $tileTime) {
                            continue;
                        }

                        $manhattan = abs($tX - $x) + abs($tY - $y);

                        if ($manhattan <= 20 && $tileTime - $tTileTime - $manhattan >= $targetSteps) {
                            $count++;
                        }
                    }
                }
            }
        }

        return $count;
    }

    private function parsePath(string $input): array
    {
        foreach (explode("\n", $input) as $y => $line) {
            foreach (str_split($line) as $x => $char) {
                if ($char === 'S') {
                    $position = new Point2D($x, $y);
                    $grid[$y][$x] = '.';
                } elseif ($char === 'E') {
                    $end = new Point2D($x, $y);
                    $grid[$y][$x] = '.';
                } else {
                    $grid[$y][$x] = $char;
                }
            }
        }

        $queue = new Queue();
        $queue->push([$position, 0]);
        $tileSteps = [];

        while (!$queue->isEmpty() && !$position->equals($end)) {
            /** @var Point2D $position */
            [$position, $time] = $queue->pop();
            $tileSteps[$position->y][$position->x] = $time;

            foreach ($position->adjacent() as $point) {
                if ($grid[$point->y][$point->x] === '.' && !isset($tileSteps[$point->y][$point->x])) {
                    $queue->push([$point, $time + 1]);
                }
            }
        }

        return $tileSteps;
    }
}
