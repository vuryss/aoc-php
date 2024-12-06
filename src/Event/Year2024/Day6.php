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
            [$blockX, $blockY] = [$x + $d[0], $y + $d[1]];

            if (($grid[$blockY][$blockX] ?? '') !== '.' || isset($visited[$blockY][$blockX])) {
                continue;
            }

            $gridCopy = $grid;
            $gridCopy[$blockY][$blockX] = '#';

            if ($this->isLooped($x, $y, $dir, $gridCopy, $visited)) {
                $blocks[$blockY][$blockX] = true;
            }
        }

        return count($blocks, COUNT_RECURSIVE) - count($blocks);
    }

    private function isLooped(int $x, int $y, string $direction, array $grid, array $visited): bool
    {
        $d = self::DELTAS[$direction];
        [$nextX, $nextY] = [$x + $d[0], $y + $d[1]];

        while (isset($grid[$nextY][$nextX]) && !isset($visited[$nextY][$nextX][$d[3]])) {
            if ($grid[$nextY][$nextX] === '#') {
                $d = self::DELTAS[$d[2]];
            } else {
                [$x, $y] = [$nextX, $nextY];
            }

            $visited[$y][$x][$d[3]] = true;
            [$nextX, $nextY] = [$x + $d[0], $y + $d[1]];

        }

        return isset($visited[$nextY][$nextX][$d[3]]);
    }

    private function getVisitedPoints(array $grid, $x, $y): iterable
    {
        $d = self::DELTAS['U'];
        $visited[$y][$x] = [$d[3] => true];
        [$nextX, $nextY] = [$x + $d[0], $y + $d[1]];

        while (isset($grid[$nextY][$nextX])) {
            if ($grid[$nextY][$nextX] === '#') {
                $d = self::DELTAS[$d[2]];
            } else {
                [$x, $y] = [$nextX, $nextY];
            }

            $visited[$y][$x][$d[3]] = true;
            [$nextX, $nextY] = [$x + $d[0], $y + $d[1]];
            yield [$x, $y, $d[3], $visited];
        }
    }
}
