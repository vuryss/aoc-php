<?php

declare(strict_types=1);

namespace App\Event\Year2023;

use App\Event\DayInterface;
use App\Util\Point2D;

class Day11 implements DayInterface
{
    public function testPart1(): iterable
    {
        yield '374' => <<<'INPUT'
            ...#......
            .......#..
            #.........
            ..........
            ......#...
            .#........
            .........#
            ..........
            .......#..
            #...#.....
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '82000210' => <<<'INPUT'
            ...#......
            .......#..
            #.........
            ..........
            ......#...
            .#........
            .........#
            ..........
            .......#..
            #...#.....
            INPUT;
    }

    public function solvePart1(string $input): string|int
    {
        return $this->solveWithExpansion($input, 1);
    }

    public function solvePart2(string $input): string|int
    {
        return $this->solveWithExpansion($input, 999999);
    }

    private function solveWithExpansion(string $input, int $expansion): int
    {
        $emptyLines = [];
        $emptyColumns = [];
        $galaxies = [];

        foreach (explode("\n", $input) as $y => $line) {
            $emptyLines[$y] = $emptyLines[$y] ?? true;

            foreach (str_split($line) as $x => $char) {
                $emptyColumns[$x] = $emptyColumns[$x] ?? true;

                if ('#' === $char) {
                    $galaxy = new Point2D($x, $y);
                    $galaxies[] = $galaxy;
                    $emptyLines[$galaxy->y] = false;
                    $emptyColumns[$galaxy->x] = false;
                }
            }
        }

        $emptyLines = array_keys(array_filter($emptyLines));
        $emptyColumns = array_keys(array_filter($emptyColumns));
        $distances = [];
        $sum = 0;

        foreach ($galaxies as $index => $galaxy) {
            foreach ($galaxies as $index2 => $otherGalaxy) {
                if ($galaxy->equals($otherGalaxy) || isset($distances[$index][$index2]) || isset($distances[$index2][$index])) {
                    continue;
                }

                $distances[$index][$index2] = $this->getDistance($galaxy, $otherGalaxy, $emptyLines, $emptyColumns, $expansion);
                $sum += $distances[$index][$index2];
            }
        }

        return $sum;
    }

    /**
     * @param array<int> $emptyLines
     * @param array<int> $emptyColumns
     */
    private function getDistance(Point2D $galaxy1, Point2D $galaxy2, array $emptyLines, array $emptyColumns, int $addedDistance = 1): int
    {
        $manhattanDistance = $galaxy1->manhattanDistance($galaxy2);
        $minX = min($galaxy1->x, $galaxy2->x);
        $maxX = max($galaxy1->x, $galaxy2->x);
        $minY = min($galaxy1->y, $galaxy2->y);
        $maxY = max($galaxy1->y, $galaxy2->y);
        $manhattanDistance += count(array_filter($emptyLines, static fn ($y) => $y >= $minY && $y <= $maxY)) * $addedDistance;
        $manhattanDistance += count(array_filter($emptyColumns, static fn ($x) => $x >= $minX && $x <= $maxX)) * $addedDistance;

        return $manhattanDistance;
    }
}
