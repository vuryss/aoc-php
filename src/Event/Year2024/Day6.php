<?php

declare(strict_types=1);

namespace App\Event\Year2024;

use App\Event\DayInterface;
use App\Util\CompassDirection;
use App\Util\Point2D;
use App\Util\RelativeDirection;
use App\Util\StringUtil;

class Day6 implements DayInterface
{
    public function testPart1(): iterable
    {
        yield '41' => <<<'INPUT'
            ....#.....
            .........#
            ..........
            ..#.......
            .......#..
            ..........
            .#..^.....
            ........#.
            #.........
            ......#...
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '6' => <<<'INPUT'
            ....#.....
            .........#
            ..........
            ..#.......
            .......#..
            ..........
            .#..^.....
            ........#.
            #.........
            ......#...
            INPUT;
    }

    public function solvePart1(string $input): string|int
    {
        $grid = StringUtil::inputToGridOfChars($input);
        $p = null;

        foreach ($grid as $y => $line) {
            foreach ($line as $x => $char) {
                if ($char === '^') {
                    $p = new Point2D($x, $y);
                }
            }
        }

        $visited = $this->getVisitedPoints($grid, $p);
        $visited = end($visited);
        $count = 0;

        foreach ($visited as $yLine) {
            $count += count($yLine);
        }

        return $count;
    }

    public function solvePart2(string $input): string|int
    {
        $grid = StringUtil::inputToGridOfChars($input);
        $p = null;

        foreach ($grid as $y => $line) {
            foreach ($line as $x => $char) {
                if ($char === '^') {
                    $p = new Point2D($x, $y);
                }
            }
        }

        $visited = $this->getVisitedPoints($grid, $p);
        $lastState = end($visited);

        $blocks = [];

        foreach ($lastState as $visitedY => $data) {
            foreach ($data as $visitedX => $visitedDirections) {
                foreach ($visitedDirections as $step => $visitedDir) {
                    $blockPosition = new Point2D($visitedX, $visitedY)->forwardFromDirection($visitedDir);

                    if (($grid[$blockPosition->y][$blockPosition->x] ?? '') !== '.') {
                        continue;
                    }

                    $visitedByFar = $visited[$step];

                    if (isset($visitedByFar[$blockPosition->y][$blockPosition->x])) {
                        continue;
                    }

                    $p = new Point2D($visitedX, $visitedY);
                    $dir = $visitedDir;
                    $newVisited = $visited[$step];
                    $newGrid = $grid;
                    $newGrid[$blockPosition->y][$blockPosition->x] = '#';

                    while (true) {
                        $next = $p->forwardFromDirection($dir);

                        if (in_array($dir, $newVisited[$next->y][$next->x] ?? [])) {
                            $blocks[$blockPosition->y][$blockPosition->x] = true;
                            break;
                        }

                        if (!isset($newGrid[$next->y][$next->x])) {
                            break;
                        }

                        if ($newGrid[$next->y][$next->x] === '#') {
                            $dir = $dir->turnRight();
                            $newVisited[$p->y][$p->x][] = $dir;
                            continue;
                        }

                        $p = $next;
                        $newVisited[$p->y][$p->x][] = $dir;
                    }
                }
            }
        }

        return count($blocks, COUNT_RECURSIVE) - count($blocks);
    }

    private function getVisitedPoints(array $grid, Point2D $point): ?array
    {
        $dir = RelativeDirection::UP;
        $steps = 0;
        $visited[$point->y][$point->x] = [$steps => $dir];
        $visitedWithDirections[$steps] = $visited;

        while (true) {
            $next = $point->forwardFromDirection($dir);

            if (!isset($grid[$next->y][$next->x])) {
                break;
            }

            if (in_array($dir, ($visited[$next->y][$next->x] ?? []))) {
                return null;
            }

            if ($grid[$next->y][$next->x] === '#') {
                $dir = $dir->turnRight();
                $visited[$point->y][$point->x][$steps] = $dir;
                continue;
            }

            $steps++;
            $point = $next;
            $visited[$point->y][$point->x][$steps] = $dir;
            $visitedWithDirections[$steps] = $visited;
        }

        return $visitedWithDirections;
    }
}
