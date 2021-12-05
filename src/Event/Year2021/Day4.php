<?php

declare(strict_types=1);

namespace App\Event\Year2021;

use App\Event\DayInterface;

class Day4 implements DayInterface
{
    public function testPart1(): iterable
    {
        yield '4512' => <<<'INPUT'
            7,4,9,5,11,17,23,2,0,14,21,24,10,16,13,6,15,25,12,22,18,20,8,19,3,26,1
            
            22 13 17 11  0
             8  2 23  4 24
            21  9 14 16  7
             6 10  3 18  5
             1 12 20 15 19
            
             3 15  0  2 22
             9 18 13 17  5
            19  8  7 25 23
            20 11 10 24  4
            14 21 16 12  6
            
            14 21 17 24  4
            10 16 15  9 19
            18  8 23 26 20
            22 11 13  6  5
             2  0 12  3  7
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '1924' => <<<'INPUT'
            7,4,9,5,11,17,23,2,0,14,21,24,10,16,13,6,15,25,12,22,18,20,8,19,3,26,1
            
            22 13 17 11  0
             8  2 23  4 24
            21  9 14 16  7
             6 10  3 18  5
             1 12 20 15 19
            
             3 15  0  2 22
             9 18 13 17  5
            19  8  7 25 23
            20 11 10 24  4
            14 21 16 12  6
            
            14 21 17 24  4
            10 16 15  9 19
            18  8 23 26 20
            22 11 13  6  5
             2  0 12  3  7
            INPUT;
    }

    public function solvePart1(string $input): string
    {
        return (string) $this->getWinningScore($input);
    }

    public function solvePart2(string $input): string
    {
        return (string) $this->getWinningScore($input, true);
    }

    private function getWinningScore(string $game, bool $last = false): int
    {
        $boardsInput = explode("\n\n", $game);
        $numbers = array_map('intval', explode(',', array_shift($boardsInput)));
        $columnMarks = $rowMarks = $results = [];
        $boards = array_map(static fn ($b): array => sscanf($b, str_repeat('%d ', 25)), $boardsInput);

        foreach ($numbers as $number) {
            foreach ($boards as $boardIndex => &$board) {
                $matchIndex = array_search($number, $board, true);

                if ($matchIndex === false) {
                    continue;
                }

                $col = $matchIndex % 5;
                $row = (int) floor($matchIndex / 5);

                $columnMarks[$boardIndex][$col] = ($columnMarks[$boardIndex][$col] ?? 0) + 1;
                $rowMarks[$boardIndex][$row] = ($rowMarks[$boardIndex][$row] ?? 0) + 1;

                $board[$matchIndex] = null;

                if ($columnMarks[$boardIndex][$col] === 5 || $rowMarks[$boardIndex][$row] === 5) {
                    $results[$boardIndex] = array_sum($board) * $number;
                    unset($boards[$boardIndex]);
                }
            }
        }

        return $last ? $results[array_key_last($results)] : $results[array_key_first($results)];
    }
}
