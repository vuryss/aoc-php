<?php

declare(strict_types=1);

namespace App\Event\Year2020;

use App\Event\DayInterface;
use Exception;

class Day22 implements DayInterface
{
    public function testPart1(): iterable
    {
        yield '306' => <<<'INPUT'
            Player 1:
            9
            2
            6
            3
            1
            
            Player 2:
            5
            8
            4
            7
            10
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '291' => <<<'INPUT'
            Player 1:
            9
            2
            6
            3
            1
            
            Player 2:
            5
            8
            4
            7
            10
            INPUT;
    }

    public function solvePart1(string $input): string
    {
        $players = explode("\n\n", $input);
        $desk1 = array_map('intval', array_slice(explode("\n", $players[0]), 1));
        $desk2 = array_map('intval', array_slice(explode("\n", $players[1]), 1));

        [, $desk] = $this->getWinner($desk1, $desk2);

        return (string) $this->calculateScore($desk);
    }

    public function solvePart2(string $input): string
    {
        $players = explode("\n\n", $input);
        $desk1 = array_map('intval', array_slice(explode("\n", $players[0]), 1));
        $desk2 = array_map('intval', array_slice(explode("\n", $players[1]), 1));

        [, $desk] = $this->getWinner($desk1, $desk2, true);

        return (string) $this->calculateScore($desk);
    }

    /**
     * @return array{int, array<int>}
     */
    private function getWinner(array $desk1, array $desk2, bool $newRules = false): array
    {
        $memory = [];
        while (count($desk1) > 0 && count($desk2) > 0) {
            $hash = $this->hash($desk1, $desk2);

            if (isset($memory[$hash])) {
                return [1, $desk1];
            }

            $memory[$hash] = true;

            $card1 = array_shift($desk1);
            $card2 = array_shift($desk2);

            if ($newRules && count($desk1) >= $card1 && count($desk2) >= $card2) {
                [$winner] = $this->getWinner(
                    array_slice($desk1, 0, $card1),
                    array_slice($desk2, 0, $card2),
                );

                if ($winner === 1) {
                    array_push($desk1, $card1, $card2);
                } else {
                    array_push($desk2, $card2, $card1);
                }

                continue;
            }

            if ($card1 > $card2) {
                array_push($desk1, $card1, $card2);
            } else {
                array_push($desk2, $card2, $card1);
            }
        }

        $winner = count($desk1) > 0 ? 1 : 2;

        return [$winner, $winner === 1 ? $desk1 : $desk2];
    }

    private function hash(array $desk1, array $desk2): string
    {
        return implode(',', $desk1) . '|' . implode(',', $desk2);
    }

    private function calculateScore(array $desk): int
    {
        $desk = array_reverse($desk);
        $value = 1;
        $total = 0;

        foreach ($desk as $card) {
            $total += $value++ * $card;
        }

        return $total;
    }
}
