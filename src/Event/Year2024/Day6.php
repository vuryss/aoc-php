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

        foreach ($this->getVisitedPoints($grid, $x, $y) as [$x, $y, $dir, $visited]) {}

        return array_reduce($visited, fn($carry, $item) => $carry + count($item), 0);
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

        $blocks = [];

        foreach ($this->getVisitedPoints($grid, $x, $y) as [$x, $y, $dir, $visited]) {
            $d = self::DELTAS[$dir];
            [$bx, $by] = [$x + $d[0], $y + $d[1]];

            if (($grid[$by][$bx] ?? '') !== '.' || isset($visited[$by][$bx])) {
                continue;
            }

            $newGrid = $grid;
            $newGrid[$by][$bx] = '#';

            if ($this->isLooped($x, $y, $dir, $newGrid, $visited)) {
                $blocks[$by][$bx] = true;
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

    private function getVisitedPoints(array $grid, $x, $y): iterable
    {
        $dir = self::DELTAS['U'];
        $visited[$y][$x] = [$dir[3] => true];
        [$nx, $ny] = [$x + $dir[0], $y + $dir[1]];

        while (isset($grid[$ny][$nx])) {
            if ($grid[$ny][$nx] === '#') {
                $dir = self::DELTAS[$dir[2]];
            } else {
                [$x, $y] = [$nx, $ny];
            }

            $visited[$y][$x][$dir[3]] = true;
            [$nx, $ny] = [$x + $dir[0], $y + $dir[1]];
            yield [$x, $y, $dir[3], $visited];
        }
    }
}
