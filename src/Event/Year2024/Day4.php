<?php

declare(strict_types=1);

namespace App\Event\Year2024;

use App\Event\DayInterface;
use App\Util\StringUtil;

class Day4 implements DayInterface
{
    public function testPart1(): iterable
    {
        yield '18' => <<<'INPUT'
            MMMSXXMASM
            MSAMXMSMSA
            AMXSXMAAMM
            MSAMASMSMX
            XMASAMXAMM
            XXAMMXXAMA
            SMSMSASXSS
            SAXAMASAAA
            MAMMMXMMMM
            MXMXAXMASX
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '9' => <<<'INPUT'
            MMMSXXMASM
            MSAMXMSMSA
            AMXSXMAAMM
            MSAMASMSMX
            XMASAMXAMM
            XXAMMXXAMA
            SMSMSASXSS
            SAXAMASAAA
            MAMMMXMMMM
            MXMXAXMASX
            INPUT;
    }

    public function solvePart1(string $input): string|int
    {
        $grid = StringUtil::inputToGridOfChars($input);
        $count = 0;

        foreach ($grid as $y => $row) {
            foreach ($row as $x => $char) {
                if ($char === 'X') {
                    // Horizontally
                    if ($x + 3 < count($row) && $row[$x + 1] === 'M' && $row[$x + 2] === 'A' && $row[$x + 3] === 'S') {
                        $count++;
                    }

                    // Horizontally (backwards)
                    if ($x - 3 >= 0 && $row[$x - 1] === 'M' && $row[$x - 2] === 'A' && $row[$x - 3] === 'S') {
                        $count++;
                    }

                    // Vertically
                    if ($y + 3 < count($grid) && $grid[$y + 1][$x] === 'M' && $grid[$y + 2][$x] === 'A' && $grid[$y + 3][$x] === 'S') {
                        $count++;
                    }

                    // Vertically (backwards)
                    if ($y - 3 >= 0 && $grid[$y - 1][$x] === 'M' && $grid[$y - 2][$x] === 'A' && $grid[$y - 3][$x] === 'S') {
                        $count++;
                    }

                    // Diagonally (bottom right)
                    if ($x + 3 < count($row) && $y + 3 < count($grid) && $grid[$y + 1][$x + 1] === 'M' && $grid[$y + 2][$x + 2] === 'A' && $grid[$y + 3][$x + 3] === 'S') {
                        $count++;
                    }

                    // Diagonally (bottom left)
                    if ($x - 3 >= 0 && $y + 3 < count($grid) && $grid[$y + 1][$x - 1] === 'M' && $grid[$y + 2][$x - 2] === 'A' && $grid[$y + 3][$x - 3] === 'S') {
                        $count++;
                    }

                    // Diagonally (top right)
                    if ($x + 3 < count($row) && $y - 3 >= 0 && $grid[$y - 1][$x + 1] === 'M' && $grid[$y - 2][$x + 2] === 'A' && $grid[$y - 3][$x + 3] === 'S') {
                        $count++;
                    }

                    // Diagonally (top left)
                    if ($x - 3 >= 0 && $y - 3 >= 0 && $grid[$y - 1][$x - 1] === 'M' && $grid[$y - 2][$x - 2] === 'A' && $grid[$y - 3][$x - 3] === 'S') {
                        $count++;
                    }
                }
            }
        }

        return $count;
    }

    public function solvePart2(string $input): string|int
    {
        $grid = StringUtil::inputToGridOfChars($input);
        $count = 0;

        foreach ($grid as $y => $row) {
            foreach ($row as $x => $char) {
                if ($char === 'M') {
                    // MAS (bottom right)
                    if (
                        $x + 2 < count($row)
                        && $y + 2 < count($grid)
                        && $grid[$y + 1][$x + 1] === 'A'
                        && $grid[$y + 2][$x + 2] === 'S'
                        && (
                            ($grid[$y][$x + 2] === 'S' && $grid[$y + 2][$x] === 'M')
                            || ($grid[$y][$x + 2] === 'M' && $grid[$y + 2][$x] === 'S')
                        )
                    ) {
                        $count++;
                    }

                    // MAS (bottom left)
                    if (
                        $x - 2 >= 0
                        && $y + 2 < count($grid)
                        && $grid[$y + 1][$x - 1] === 'A'
                        && $grid[$y + 2][$x - 2] === 'S'
                        && (
                            ($grid[$y][$x - 2] === 'S' && $grid[$y + 2][$x] === 'M')
                            || ($grid[$y][$x - 2] === 'M' && $grid[$y + 2][$x] === 'S')
                        )
                    ) {
                        $count++;
                    }

                    // MAS (top right)
                    if (
                        $x + 2 < count($row)
                        && $y - 2 >= 0
                        && $grid[$y - 1][$x + 1] === 'A'
                        && $grid[$y - 2][$x + 2] === 'S'
                        && (
                            ($grid[$y][$x + 2] === 'S' && $grid[$y - 2][$x] === 'M')
                            || ($grid[$y][$x + 2] === 'M' && $grid[$y - 2][$x] === 'S')
                        )
                    ) {
                        $count++;
                    }

                    // MAS (top left)
                    if (
                        $x - 2 >= 0
                        && $y - 2 >= 0
                        && $grid[$y - 1][$x - 1] === 'A'
                        && $grid[$y - 2][$x - 2] === 'S'
                        && (
                            ($grid[$y][$x - 2] === 'S' && $grid[$y - 2][$x] === 'M')
                            || ($grid[$y][$x - 2] === 'M' && $grid[$y - 2][$x] === 'S')
                        )
                    ) {
                        $count++;
                    }
                }
            }
        }

        return $count / 2;
    }
}
