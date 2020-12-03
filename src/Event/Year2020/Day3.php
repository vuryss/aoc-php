<?php

declare(strict_types=1);

namespace App\Event\Year2020;

use App\Event\Day;

class Day3 extends Day
{
    const YEAR = 2020;
    const DAY = 3;

    public function testPart1(): iterable
    {
        yield '7' => <<<'INPUT'
            ..##.......
            #...#...#..
            .#....#..#.
            ..#.#...#.#
            .#...##..#.
            ..#.##.....
            .#.#.#....#
            .#........#
            #.##...#...
            #...##....#
            .#..#...#.#
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '336' => <<<'INPUT'
            ..##.......
            #...#...#..
            .#....#..#.
            ..#.#...#.#
            .#...##..#.
            ..#.##.....
            .#.#.#....#
            .#........#
            #.##...#...
            #...##....#
            .#..#...#.#
            INPUT;
    }

    public function solvePart1(string $input): string
    {
        $map = $this->generateMap($input);

        return (string) $this->countTreeForSlope(3, 1, $map);
    }

    public function solvePart2(string $input): string
    {
        $map = $this->generateMap($input);

        return (string) array_product([
            $this->countTreeForSlope(1, 1, $map),
            $this->countTreeForSlope(3, 1, $map),
            $this->countTreeForSlope(5, 1, $map),
            $this->countTreeForSlope(7, 1, $map),
            $this->countTreeForSlope(1, 2, $map),
        ]);
    }

    private function generateMap(string $input): array
    {
        $map = [];

        foreach (explode("\n", $input) as $y => $line) {
            foreach (str_split($line) as $x => $item) {
                $map[$y][$x] = $item === '#' ? 1 : 0;
            }
        }

        return $map;
    }

    private function countTreeForSlope(int $x, int $y, array $map): int
    {
        $trees = 0;
        $height = count($map);
        $width = count($map[0]);
        $posX = $posY = 0;

        do {
            $trees += $map[$posY][$posX];
            $posX = ($posX + $x) % $width;
            $posY += $y;
        } while ($posY < $height);

        return $trees;
    }
}
