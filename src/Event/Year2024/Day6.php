<?php

declare(strict_types=1);

namespace App\Event\Year2024;

use App\Event\DayInterface;
use App\Util\StringUtil;

class Day6 implements DayInterface
{
    private const array DELTAS = [
        'U' => [0, -1, 'R', 'U'],
        'R' => [1, 0, 'D', 'R'],
        'D' => [0, 1, 'L', 'D'],
        'L' => [-1, 0, 'U', 'L'],
    ];

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
        [$x, $y] = [0, 0];

        foreach ($grid as $y => $line) {
            foreach ($line as $x => $char) {
                if ($char === '^') {
                    break 2;
                }
            }
        }

        $visited = $this->getVisitedPoints($grid, $x, $y);

        return array_reduce(end($visited), fn($carry, $item) => $carry + count($item), 0);
    }

    public function solvePart2(string $input): string|int
    {
        $grid = StringUtil::inputToGridOfChars($input);
        [$x, $y] = [0, 0];

        foreach ($grid as $y => $line) {
            foreach ($line as $x => $char) {
                if ($char === '^') {
                    break 2;
                }
            }
        }

        $visited = $this->getVisitedPoints($grid, $x, $y);
        $blocks = [];

        foreach ($visited[array_key_last($visited)] as $visitedY => $data) {
            foreach ($data as $visitedX => $visitedDirections) {
                foreach ($visitedDirections as $visitedDir => $step) {
                    $d = self::DELTAS[$visitedDir];
                    [$bx, $by] = [$visitedX + $d[0], $visitedY + $d[1]];

                    if (($grid[$by][$bx] ?? '') !== '.' || isset($visited[$step][$by][$bx])) {
                        continue;
                    }

                    $newGrid = $grid;
                    $newGrid[$by][$bx] = '#';

                    if ($this->isLooped($visitedX, $visitedY, $visitedDir, $newGrid, $visited[$step])) {
                        $blocks[$by][$bx] = true;
                    }
                }
            }
        }

        return count($blocks, COUNT_RECURSIVE) - count($blocks);
    }

    private function isLooped(int $x, int $y, string $direction, array $grid, array $visited): bool
    {
        $dir = self::DELTAS[$direction];
        [$nx, $ny] = [$x + $dir[0], $y + $dir[1]];

        while (isset($grid[$ny][$nx]) && !isset($visited[$ny][$nx][$dir[3]])) {
            if ($grid[$ny][$nx] === '#') {
                $dir = self::DELTAS[$dir[2]];
            } else {
                [$x, $y] = [$nx, $ny];
            }

            $visited[$y][$x][$dir[3]] = true;
            [$nx, $ny] = [$x + $dir[0], $y + $dir[1]];
        }

        return isset($visited[$ny][$nx][$dir[3]]);
    }

    private function getVisitedPoints(array $grid, $x, $y): array
    {
        $dir = self::DELTAS['U'];
        $steps = 0;
        $visited[$y][$x] = [$dir[3] => $steps];
        $visitedWithDirections[$steps] = $visited;

        while (true) {
            [$nx, $ny] = [$x + $dir[0], $y + $dir[1]];

            if (!isset($grid[$ny][$nx])) {
                break;
            }

            if ($grid[$ny][$nx] === '#') {
                $dir = self::DELTAS[$dir[2]];
                $visited[$y][$x][$dir[3]] = $steps;
                continue;
            }

            $steps++;
            [$x, $y] = [$nx, $ny];
            $visited[$y][$x][$dir[3]] = $steps;
            $visitedWithDirections[$steps] = $visited;
        }

        return $visitedWithDirections;
    }
}
