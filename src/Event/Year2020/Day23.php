<?php

declare(strict_types=1);

namespace App\Event\Year2020;

use App\Event\DayInterface;

class Day23 implements DayInterface
{
    private int $highestCupLabel = 0;

    public function testPart1(): iterable
    {
        yield '67384529' => <<<'INPUT'
            389125467
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '149245887792' => <<<'INPUT'
            389125467
            INPUT;
    }

    public function solvePart1(string $input): string
    {
        $cups = $this->generateCups($input);
        $current = array_key_first($cups);

        $cups = $this->playGame($cups, $current, 100);

        $current = $cups[1];
        $result = '';

        do {
            $result .= $current;
            $current = $cups[$current];
        } while ($current !== 1);

        return $result;
    }

    public function solvePart2(string $input): string
    {
        $cups = $this->generateCups($input, true);
        $current = array_key_first($cups);

        $cups = $this->playGame($cups, $current, 10000000);

        return bcmul((string) $cups[1], (string) $cups[$cups[1]]);
    }

    /**
     * @return array<int, int>
     */
    private function generateCups(string $input, bool $upToMillion = false): array
    {
        $cupsInput = str_split($input);
        $cups = [];
        $lastAddedCup = null;
        $this->highestCupLabel = 0;

        foreach ($cupsInput as $label) {
            $label = (int) $label;
            $this->highestCupLabel = max($label, $this->highestCupLabel);
            if ($lastAddedCup) {
                $cups[$lastAddedCup] = $label;
            }
            $lastAddedCup = $label;
        }

        if ($upToMillion) {
            for ($i = $this->highestCupLabel + 1; $i <= 1000000; $i++) {
                $cups[$lastAddedCup] = $i;
                $lastAddedCup = $i;
            }

            $this->highestCupLabel = 1000000;
        }

        $cups[$lastAddedCup] = array_key_first($cups);

        return $cups;
    }

    /**
     * @return array<int, int>
     */
    private function playGame(array $cups, int $current, int $moves): array
    {
        for ($i = 1; $i <= $moves; $i++) {
            $cup = $current;
            $removedIds = [];
            $removedStartCup = $cups[$cup];

            for ($j = 1; $j <= 3; $j++) {
                $cup = $cups[$cup];
                $removedIds[$cup] = true;
                $removedEndCup = $cup;
            }

            $cups[$current] = $cups[$removedEndCup];
            $destinationId = null;

            do {
                $destinationId = $destinationId !== null ? $destinationId - 1 : $current - 1;
                if (!isset($cups[$destinationId])) {
                    $destinationId = $this->highestCupLabel;
                }
            } while (isset($removedIds[$destinationId]));

            $destinationNextCup = $cups[$destinationId];
            $cups[$destinationId] = $removedStartCup;
            $cups[$removedEndCup] = $destinationNextCup;
            $current = $cups[$current];
        }

        return $cups;
    }
}
