<?php

declare(strict_types=1);

namespace App\Event\Year2023;

use App\Event\DayInterface;

class Day2 implements DayInterface
{
    public function testPart1(): iterable
    {
        yield '8' => <<<'INPUT'
            Game 1: 3 blue, 4 red; 1 red, 2 green, 6 blue; 2 green
            Game 2: 1 blue, 2 green; 3 green, 4 blue, 1 red; 1 green, 1 blue
            Game 3: 8 green, 6 blue, 20 red; 5 blue, 4 red, 13 green; 5 green, 1 red
            Game 4: 1 green, 3 red, 6 blue; 3 green, 6 red; 3 green, 15 blue, 14 red
            Game 5: 6 red, 1 blue, 3 green; 2 blue, 1 red, 2 green
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '2286' => <<<'INPUT'
            Game 1: 3 blue, 4 red; 1 red, 2 green, 6 blue; 2 green
            Game 2: 1 blue, 2 green; 3 green, 4 blue, 1 red; 1 green, 1 blue
            Game 3: 8 green, 6 blue, 20 red; 5 blue, 4 red, 13 green; 5 green, 1 red
            Game 4: 1 green, 3 red, 6 blue; 3 green, 6 red; 3 green, 15 blue, 14 red
            Game 5: 6 red, 1 blue, 3 green; 2 blue, 1 red, 2 green
            INPUT;
    }

    public function solvePart1(string $input): string|int
    {
        $lines = explode("\n", $input);
        $sum = 0;

        foreach ($lines as $line) {
            preg_match('/\d+/', $line, $matches);
            $gameNumber = (int) $matches[0];
            ['red' => $maxRed, 'green' => $maxGreen, 'blue' => $maxBlue] = $this->getMaxCubes($line);

            if ($maxRed <= 12 && $maxBlue <= 14 && $maxGreen <= 13) {
                $sum += $gameNumber;
            }
        }

        return $sum;
    }

    public function solvePart2(string $input): string|int
    {
        $lines = explode("\n", $input);
        $sum = 0;

        foreach ($lines as $line) {
            ['red' => $maxRed, 'green' => $maxGreen, 'blue' => $maxBlue] = $this->getMaxCubes($line);

            $sum += $maxRed * $maxGreen * $maxBlue;
        }

        return $sum;
    }

    /**
     * @psalm-return array{red: int, green: int, blue: int}
     */
    private function getMaxCubes($line): array
    {
        $games = explode('; ', explode(': ', $line)[1]);
        $maxGreen = $maxRed = $maxBlue = 0;

        foreach ($games as $game) {
            preg_match('/(\d+)\sblue/', $game, $matches);
            $maxBlue = max($maxBlue, isset($matches[1]) ? (int) $matches[1] : 0);

            preg_match('/(\d+)\sred/', $game, $matches);
            $maxRed = max($maxRed, isset($matches[1]) ? (int) $matches[1] : 0);

            preg_match('/(\d+)\sgreen/', $game, $matches);
            $maxGreen = max($maxGreen, isset($matches[1]) ? (int) $matches[1] : 0);
        }

        return ['red' => $maxRed, 'green' => $maxGreen, 'blue' => $maxBlue];
    }
}
