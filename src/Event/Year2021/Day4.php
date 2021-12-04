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
        $input = explode("\n\n", $game);
        $numbers = array_shift($input);
        $boardsInput = $input;
        $boards = [];
        $marked = [];

        foreach ($boardsInput as $index => $boardInput) {
            $lines = explode("\n", $boardInput);
            foreach ($lines as $lineNum => $line) {
                $boards[$index][$lineNum] = array_map('intval', preg_split('/\s+/', trim($line)));
                $marked[$index][$lineNum] = [0, 0, 0, 0, 0];
            }
        }

        $numbers = array_map('intval', explode(',', $numbers));
        $winBoard = null;
        $winNumber = null;

        $winBoards = [];

        foreach ($numbers as $number) {
            foreach ($boards as $bid =>  $board) {
                foreach ($board as $lid =>  $line) {
                    foreach ($line as $nid => $num) {
                        if ($num === $number) {
                            $marked[$bid][$lid][$nid] = 1;
                        }
                    }
                }
            }

            foreach ($marked as $bid => $board) {
                $countCols = [];
                foreach ($board as $line) {
                    if (array_sum($line) === 5) {
                        $winBoard = $bid;
                        $winNumber = $number;
                        $winBoards[$bid] = true;

                        if (!$last || count($winBoards) === count($boards)) {
                            break 3;
                        }
                    }
                }

                for ($i = 0; $i < 5; $i++) {
                    $countCols[$i] = array_sum(array_column($board, $i));
                }

                foreach ($countCols as $countCol) {
                    if ($countCol === 5) {
                        $winBoard = $bid;
                        $winNumber = $number;
                        $winBoards[$bid] = true;

                        if (!$last || count($winBoards) === count($boards)) {
                            break 3;
                        }
                    }
                }
            }
        }

        $sum = 0;

        foreach ($marked[$winBoard] as $lineIndex => $line) {
            foreach ($line as $position => $marked) {
                if ($marked !== 1) {
                    $sum += $boards[$winBoard][$lineIndex][$position];
                }
            }
        }

        return $sum * $winNumber;
    }
}
