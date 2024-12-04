<?php

declare(strict_types=1);

namespace App\Event\Year2024;

use App\Event\DayInterface;
use App\Util\Delta;
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
        $words = [];

        foreach ($grid as $y => $row) {
            foreach ($row as $x => $char) {
                for ($n = 1; $n < 4; $n++) {
                    foreach (Delta::SURROUNDING as $d) {
                        $key = "{$x},{$y},{$d[0]},{$d[1]}";
                        $words[$key] = $words[$key] ?? $char;
                        $words[$key] .= $grid[$y + $n * $d[0]][$x + $n * $d[1]] ?? '';
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
                $word = array_map(fn ($d) => $grid[$y + $d[0]][$x + $d[1]] ?? '', Delta::DIAGONAL_INCLUSIVE);
                $count += preg_match('/ASSMM|AMMSS|ASMMS|AMSSM/', implode('', $word));
            }
        }

        return $count;
    }
}
