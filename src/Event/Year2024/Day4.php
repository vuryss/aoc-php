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
        $d = [-1, 0, 1];
        $words = [];

        foreach ($grid as $y => $row) {
            foreach ($row as $x => $char) {
                if ($char === 'X') {
                    for ($n = 1; $n < 4; $n++) {
                        foreach ($d as $dx) {
                            foreach ($d as $dy) {
                                $key = "{$x},{$y},{$dx},{$dy}";
                                $words[$key] = $words[$key] ?? $char;
                                $words[$key] .= $grid[$y + $n * $dy][$x + $n * $dx] ?? '';
                            }
                        }
                    }
                }
            }
        }

        return count(array_filter($words, fn($word) => $word === 'XMAS'));
    }

    public function solvePart2(string $input): string|int
    {
        $grid = StringUtil::inputToGridOfChars($input);
        $count = 0;

        foreach ($grid as $y => $row) {
            foreach ($row as $x => $char) {
                if ($char == 'A') {
                    $a = ($grid[$y + 1][$x + 1] ?? '') . ($grid[$y - 1][$x - 1] ?? '');
                    $b = ($grid[$y + 1][$x - 1] ?? '') . ($grid[$y - 1][$x + 1] ?? '');

                    if (preg_match('/MS|SM/', $a) && preg_match('/MS|SM/', $b)) {
                        $count++;
                    }
                }
            }
        }

        return $count;
    }
}
