<?php

declare(strict_types=1);

namespace App\Event\Year2021;

use App\Event\DayInterface;

class Day21 implements DayInterface
{
    private array $memory = [];
    private array $nextPositions = [];

    public function testPart1(): iterable
    {
        yield '739785' => <<<'INPUT'
            Player 1 starting position: 4
            Player 2 starting position: 8
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '444356092776315' => <<<'INPUT'
            Player 1 starting position: 4
            Player 2 starting position: 8
            INPUT;
    }

    public function solvePart1(string $input): string|int
    {
        $lines = explode("\n", $input);
        $players = [
            1 => (int) $lines[0][strlen($lines[0]) - 1],
            2 => (int) $lines[1][strlen($lines[1]) - 1],
        ];
        $scores = [
            1 => 0,
            2 => 0,
        ];
        $rolls = 0;

        while (true) {
            foreach ($players as $playerId => $position) {
                $roll = $this->deterministicDiceThrow()
                    + $this->deterministicDiceThrow()
                    + $this->deterministicDiceThrow();
                $rolls += 3;

                $position += $roll;
                $position = $position > 10 ? $position - (int) (($position - 1) / 10) * 10 : $position;

                $scores[$playerId] += $position;
                $players[$playerId] = $position;

                foreach ($scores as $score) {
                    if ($score >= 1000) {
                        return $scores[$playerId === 1 ? 2 : 1] * $rolls;
                    }
                }
            }
        }
    }

    public function solvePart2(string $input): string|int
    {
        $lines = explode("\n", $input);
        $players = [
            1 => (int) $lines[0][strlen($lines[0]) - 1],
            2 => (int) $lines[1][strlen($lines[1]) - 1],
        ];

        $nextScores = array_fill_keys(range(1, 10), []);

        foreach ($nextScores as $position => $_) {
            for ($i = 1; $i <= 3; $i++) {
                for ($j = 1; $j <= 3; $j++) {
                    for ($k = 1; $k <= 3; $k++) {
                        $nextPosition = $position + $i + $j + $k;
                        $nextPosition = $nextPosition > 10 ? $nextPosition - 10 : $nextPosition;
                        $nextScores[$position][$nextPosition] = ($nextScores[$position][$nextPosition] ?? 0) + 1;
                    }
                }
            }
        }

        $this->nextPositions = $nextScores;
        $this->memory = [];

        return max($this->play(0, $players[1], 0, $players[2]));
    }

    private function play(int $p1score, int $p1position, int $p2score, int $p2position): array
    {
        $hash = serialize(func_get_args());

        if (isset($this->memory[$hash])) {
            return $this->memory[$hash];
        }

        if ($p1score >= 21) {
            return [1, 0];
        }

        if ($p2score >= 21) {
            return [0, 1];
        }

        $p1wins = 0;
        $p2wins = 0;

        foreach ($this->nextPositions[$p1position] as $nextPosition => $times) {
            [$p1winsTemp, $p2winsTemp] = $this->play(
                $p2score,
                $p2position,
                $p1score + $nextPosition,
                $nextPosition,
            );
            $p2wins += $p1winsTemp * $times;
            $p1wins += $p2winsTemp * $times;
        }

        $this->memory[$hash] = [$p1wins, $p2wins];

        return [$p1wins, $p2wins];
    }

    private function deterministicDiceThrow(): int
    {
        static $number = 0;

        $number = ++$number > 100 ? 1 : $number;

        return $number;
    }
}
