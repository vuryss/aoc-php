<?php

declare(strict_types=1);

namespace App\Event\Year2024;

use App\Event\DayInterface;
use App\Util\CompassDirection;
use App\Util\Point2D;
use App\Util\StringUtil;
use Ds\PriorityQueue;

class Day16 implements DayInterface
{
    public function testPart1(): iterable
    {
        yield '7036' => <<<'INPUT'
            ###############
            #.......#....E#
            #.#.###.#.###.#
            #.....#.#...#.#
            #.###.#####.#.#
            #.#.#.......#.#
            #.#.#####.###.#
            #...........#.#
            ###.#.#####.#.#
            #...#.....#.#.#
            #.#.#.###.#.#.#
            #.....#...#.#.#
            #.###.#.#.#.#.#
            #S..#.....#...#
            ###############
            INPUT;

        yield '11048' => <<<'INPUT'
            #################
            #...#...#...#..E#
            #.#.#.#.#.#.#.#.#
            #.#.#.#...#...#.#
            #.#.#.#.###.#.#.#
            #...#.#.#.....#.#
            #.#.#.#.#.#####.#
            #.#...#.#.#.....#
            #.#.#####.#.###.#
            #.#.#.......#...#
            #.#.###.#####.###
            #.#.#...#.....#.#
            #.#.#.#####.###.#
            #.#.#.........#.#
            #.#.#.#########.#
            #S#.............#
            #################
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '45' => <<<'INPUT'
            ###############
            #.......#....E#
            #.#.###.#.###.#
            #.....#.#...#.#
            #.###.#####.#.#
            #.#.#.......#.#
            #.#.#####.###.#
            #...........#.#
            ###.#.#####.#.#
            #...#.....#.#.#
            #.#.#.###.#.#.#
            #.....#...#.#.#
            #.###.#.#.#.#.#
            #S..#.....#...#
            ###############
            INPUT;

        yield '64' => <<<'INPUT'
            #################
            #...#...#...#..E#
            #.#.#.#.#.#.#.#.#
            #.#.#.#...#...#.#
            #.#.#.#.###.#.#.#
            #...#.#.#.....#.#
            #.#.#.#.#.#####.#
            #.#...#.#.#.....#
            #.#.#####.#.###.#
            #.#.#.......#...#
            #.#.###.#####.###
            #.#.#...#.....#.#
            #.#.#.#####.###.#
            #.#.#.........#.#
            #.#.#.#########.#
            #S#.............#
            #################
            INPUT;
    }

    public function solvePart1(string $input): string|int
    {
        $grid = StringUtil::inputToGridOfChars($input);
        $position = null;

        foreach ($grid as $y => $line) {
            foreach ($line as $x => $char) {
                if ($char === 'S') {
                    $position = new Point2D($x, $y);
                    break 2;
                }
            }
        }

        $queue = new PriorityQueue();
        $queue->push([$position, CompassDirection::EAST, 0, []], 0);
        $minStepsToNodeWithDirection = [];

        while (!$queue->isEmpty()) {
            /** @var Point2D $position */
            /** @var CompassDirection $direction */
            [$position, $direction, $score] = $queue->pop();

            if (
                isset($minStepsToNodeWithDirection[$position->y][$position->x][$direction->value])
                && $minStepsToNodeWithDirection[$position->y][$position->x][$direction->value] <= $score
            ) {
                continue;
            }

            $minStepsToNodeWithDirection[$position->y][$position->x][$direction->value] = $score;

            if ($grid[$position->y][$position->x] === 'E') {
                return $score;
            }

            foreach ([$direction, $direction->turnLeft(), $direction->turnRight()] as $newDirection) {
                $nextPoint = $newDirection->movePoint($position);

                if ($grid[$nextPoint->y][$nextPoint->x] === '#') {
                    continue;
                }

                $newScore = $score + 1 + ($newDirection !== $direction ? 1000 : 0);
                $queue->push([$nextPoint, $newDirection, $newScore], -$newScore);
            }
        }

        return -1;
    }

    public function solvePart2(string $input): string|int
    {
        $grid = StringUtil::inputToGridOfChars($input);

        foreach ($grid as $y => $line) {
            foreach ($line as $x => $char) {
                if ($char === 'S') {
                    $position = new Point2D($x, $y);
                    break 2;
                }
            }
        }

        $queue = new PriorityQueue();
        $queue->push([$position, CompassDirection::EAST, 0, []], 0);
        $minScore = PHP_INT_MAX;
        $minStepsToNodeWithDirection = [];
        $bestNodes = [];

        while (!$queue->isEmpty()) {
            /** @var Point2D $position */
            /** @var CompassDirection $direction */
            [$position, $direction, $score, $visited] = $queue->pop();

            if (
                isset($minStepsToNodeWithDirection[$position->y][$position->x][$direction->value])
                && $minStepsToNodeWithDirection[$position->y][$position->x][$direction->value] < $score
            ) {
                continue;
            }

            $visited[$position->y][$position->x] = true;
            $minStepsToNodeWithDirection[$position->y][$position->x][$direction->value] = $score;

            if ($score > $minScore) {
                break;
            }

            if ($grid[$position->y][$position->x] === 'E') {
                foreach ($visited as $y => $row) {
                    foreach ($row as $x => $visited) {
                        $bestNodes[$y][$x] = true;
                    }
                }

                $minScore = min($minScore, $score);
            }

            foreach ([$direction, $direction->turnLeft(), $direction->turnRight()] as $newDirection) {
                $nextPoint = $newDirection->movePoint($position);

                if ($grid[$nextPoint->y][$nextPoint->x] === '#') {
                    continue;
                }

                $newScore = $score + 1 + ($newDirection !== $direction ? 1000 : 0);
                $queue->push([$nextPoint, $newDirection, $newScore, $visited], -$newScore);
            }
        }

        return count($bestNodes, COUNT_RECURSIVE) - count($bestNodes);
    }
}
