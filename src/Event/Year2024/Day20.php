<?php

declare(strict_types=1);

namespace App\Event\Year2024;

use App\Event\DayInterface;
use App\Util\Point2D;
use App\Util\StringUtil;
use Ds\Queue;

class Day20 implements DayInterface
{
    public function testPart1(): iterable
    {
        yield 'result' => <<<'INPUT'
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
        yield 'result' => <<<'INPUT'
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
        $grid = StringUtil::inputToGridOfChars($input);

        foreach ($grid as $y => $line) {
            foreach ($line as $x => $char) {
                if ($char === 'S') {
                    $start = new Point2D($x, $y);
                    $grid[$y][$x] = '.';
                } elseif ($char === 'E') {
                    $end = new Point2D($x, $y);
                    $grid[$y][$x] = '.';
                }
            }
        }

        $queue = new Queue();
        $queue->push([$start, 0]);
        $maxTime = null;
        $tileSteps = [];

        while (!$queue->isEmpty()) {
            /** @var Point2D $position */
            [$position, $time] = $queue->pop();
            $tileSteps[$position->y][$position->x] = $time;

            if ($position->equals($end)) {
                $maxTime = $time;
                break;
            }

            foreach ($position->adjacent() as $point) {
                if ($grid[$point->y][$point->x] === '.' && !isset($tileSteps[$point->y][$point->x])) {
                    $queue->push([$point, $time + 1]);
                }
            }
        }

        $queue = new Queue();
        $queue->push([$start, 0, false, []]);
        $count = 0;

        while (!$queue->isEmpty()) {
            /** @var Point2D $position */
            [$position, $time, $cheated, $visited] = $queue->pop();

            if (isset($visited[$position->y][$position->x])) {
                continue;
            }

            $visited[$position->y][$position->x] = true;

            if ($position->equals($end)) {
                continue;
            }

            foreach ($position->adjacent() as $point) {
                if (($grid[$point->y][$point->x] ?? '') === '.') {
                    $queue->push([$point, $time + 1, $cheated, $visited]);
                }

                if ($cheated) {
                    continue;
                }

                foreach ($point->adjacent() as $next) {
                    if (($grid[$next->y][$next->x] ?? '') === '.' && !isset($visited[$next->y][$next->x])) {
                        $newTime = ($maxTime - $tileSteps[$next->y][$next->x]) + $time + 2;
                        $savedTime = $maxTime - $newTime;
                        if ($savedTime >= 100) {
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
        $grid = StringUtil::inputToGridOfChars($input);

        foreach ($grid as $y => $line) {
            foreach ($line as $x => $char) {
                if ($char === 'S') {
                    $start = new Point2D($x, $y);
                    $grid[$y][$x] = '.';
                } elseif ($char === 'E') {
                    $end = new Point2D($x, $y);
                    $grid[$y][$x] = '.';
                }
            }
        }

        $queue = new Queue();
        $queue->push([$start, 0]);
        $tileSteps = [];
        $position = $start;

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

        $count = 0;

        foreach ($tileSteps as $y => $line) {
            foreach ($line as $x => $tileTime) {


                foreach ($tileSteps as $tY => $tLine) {
                    foreach ($tLine as $tX => $tTileTime) {

                        if ($tTileTime >= $tileTime) {
                            continue;
                        }

                        $manhattan = abs($tX - $x) + abs($tY - $y);

                        if ($manhattan > 20) {
                            continue;
                        }

                        $savedTime = $tileTime - $tTileTime - $manhattan;

                        if ($savedTime >= 100) {
                            $count++;
                        }
                    }
                }

            }
        }

        return $count;
    }
}
