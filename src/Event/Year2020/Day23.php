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
        } while (1 !== $current);

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
            assert(null !== $lastAddedCup);
            for ($i = $this->highestCupLabel + 1; $i <= 1000000; $i++) {
                $cups[$lastAddedCup] = $i;
                $lastAddedCup = $i;
            }

            $this->highestCupLabel = 1000000;
        }

        $cups[(int) $lastAddedCup] = (int) array_key_first($cups);

        return $cups;
    }

    /**
     * @return array<int, int>
     */
    private function playGame(array $cups, int $current, int $moves): array
    {
        for ($i = 1; $i <= $moves; $i++) {
            $c1 = $cups[$current];
            $c2 = $cups[$c1];
            $c3 = $cups[$c2];

            $cups[$current] = $cups[$c3];
            $destinationId = $current;

            do {
                isset($cups[--$destinationId]) || $destinationId = $this->highestCupLabel;
            } while ($destinationId === $c1 || $destinationId === $c2 || $destinationId === $c3);

            $cups[$c3] = $cups[$destinationId];
            $cups[$destinationId] = $c1;
            $current = $cups[$current];
        }

        return $cups;
    }
}
