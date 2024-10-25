<?php

declare(strict_types=1);

namespace App\Event\Year2023;

use App\Event\DayInterface;

class Day4 implements DayInterface
{
    public function testPart1(): iterable
    {
        yield '13' => <<<'INPUT'
            Card 1: 41 48 83 86 17 | 83 86  6 31 17  9 48 53
            Card 2: 13 32 20 16 61 | 61 30 68 82 17 32 24 19
            Card 3:  1 21 53 59 44 | 69 82 63 72 16 21 14  1
            Card 4: 41 92 73 84 69 | 59 84 76 51 58  5 54 83
            Card 5: 87 83 26 28 32 | 88 30 70 12 93 22 82 36
            Card 6: 31 18 13 56 72 | 74 77 10 23 35 67 36 11
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '30' => <<<'INPUT'
            Card 1: 41 48 83 86 17 | 83 86  6 31 17  9 48 53
            Card 2: 13 32 20 16 61 | 61 30 68 82 17 32 24 19
            Card 3:  1 21 53 59 44 | 69 82 63 72 16 21 14  1
            Card 4: 41 92 73 84 69 | 59 84 76 51 58  5 54 83
            Card 5: 87 83 26 28 32 | 88 30 70 12 93 22 82 36
            Card 6: 31 18 13 56 72 | 74 77 10 23 35 67 36 11
            INPUT;
    }

    public function solvePart1(string $input): string|int
    {
        $lines = explode("\n", $input);
        $sum = 0;

        foreach ($lines as $line) {
            [, $score] = $this->parseCard($line);

            $sum += $score > 0 ? (int) (2 ** ($score - 1)) : 0;
        }

        return $sum;
    }

    public function solvePart2(string $input): string|int
    {
        $lines = explode("\n", $input);
        $cardsWon = array_fill(1, count($lines), 1);

        foreach ($lines as $index => $line) {
            [$cardNumber, $score] = $this->parseCard($line);

            for ($i = $cardNumber + 1; $i <= $cardNumber + $score; $i++) {
                $cardsWon[$i] += $cardsWon[$index + 1];
            }
        }

        return array_sum($cardsWon);
    }

    private function parseCard(string $line): array
    {
        preg_match('/\d+/', $line, $matches);
        [$winningPart, $minePart] = preg_split('/\s*\|\s*/', preg_split('/\d:\s*/', $line)[1]);
        $winningNumbers = array_map('intval', preg_split('/\s+/', $winningPart));
        $mineNumbers = array_map('intval', preg_split('/\s+/', $minePart));

        return [(int) $matches[0], count(array_intersect($winningNumbers, $mineNumbers))];
    }
}
