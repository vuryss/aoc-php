<?php

declare(strict_types=1);

namespace App\Event\Year2021;

use App\Event\DayInterface;

class Day20 implements DayInterface
{
    private const DELTAS = [
        [-1, -1],
        [-1, 0],
        [-1, 1],
        [0, -1],
        [0, 0],
        [0, 1],
        [1, -1],
        [1, 0],
        [1, 1],
    ];

    public function testPart1(): iterable
    {
        yield '35' => <<<'INPUT'
            ..#.#..#####.#.#.#.###.##.....###.##.#..###.####..#####..#....#..#..##..###..######.###...####..#..#####..##..#.#####...##.#.#..#.##..#.#......#.###.######.###.####...#.##.##..#..#..#####.....#.#....###..#.##......#.....#..#..#..##..#...##.######.####.####.#.#...#.......#..#.#.#...####.##.#......#..#...##.#.##..#...##.#.##..###.#......#.#.......#.#.#.####.###.##...#.....####.#..#..#.##.#....##..#.####....##...##..#...#......#.#.......#.......##..####..#...#.#.#...##..#.#..###..#####........#..####......#..#
            
            #..#.
            #....
            ##..#
            ..#..
            ..###
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '3351' => <<<'INPUT'
            ..#.#..#####.#.#.#.###.##.....###.##.#..###.####..#####..#....#..#..##..###..######.###...####..#..#####..##..#.#####...##.#.#..#.##..#.#......#.###.######.###.####...#.##.##..#..#..#####.....#.#....###..#.##......#.....#..#..#..##..#...##.######.####.####.#.#...#.......#..#.#.#...####.##.#......#..#...##.#.##..#...##.#.##..###.#......#.#.......#.#.#.####.###.##...#.....####.#..#..#.##.#....##..#.####....##...##..#...#......#.#.......#.......##..####..#...#.#.#...##..#.#..###..#####........#..####......#..#
            
            #..#.
            #....
            ##..#
            ..#..
            ..###
            INPUT;
    }

    public function solvePart1(string $input): string|int
    {
        return $this->enhanceGrid($input, 2);
    }

    public function solvePart2(string $input): string|int
    {
        return $this->enhanceGrid($input, 50);
    }

    public function enhanceGrid(string $input, int $times): int
    {
        [$pattern, $image] = explode("\n\n", $input);
        $grid = array_map('str_split', explode("\n", $image));

        $minX = $minY = -2;
        $maxY = count($grid) + 2;
        $maxX = count($grid[0]) + 2;
        $background = '.';

        for ($i = 0; $i < $times; $i++) {
            $newGrid = $grid;

            for ($y = $minY; $y <= $maxY; $y++) {
                for ($x = $minX; $x <= $maxX; $x++) {
                    $binaryNumber = '';

                    foreach (self::DELTAS as [$dy, $dx]) {
                        $binaryNumber .= ($grid[$y + $dy][$x + $dx] ?? $background) === '.' ? '0' : '1';
                    }

                    $newGrid[$y][$x] = $pattern[bindec($binaryNumber)];
                }
            }

            $background = $background === '.' ? $pattern[0] : $pattern[511];
            $minX -= 2;
            $minY -= 2;
            $maxX += 2;
            $maxY += 2;
            $grid = $newGrid;
        }

        $count = 0;

        for ($y = $minY; $y <= $maxY; $y++) {
            for ($x = $minX; $x <= $maxX; $x++) {
                if (($grid[$y][$x] ?? $background) === '#') {
                    $count++;
                }
            }
        }

        return $count;
    }
}
