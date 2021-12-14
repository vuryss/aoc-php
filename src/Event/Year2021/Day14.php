<?php

declare(strict_types=1);

namespace App\Event\Year2021;

use App\Event\DayInterface;

class Day14 implements DayInterface
{
    public function testPart1(): iterable
    {
        yield '1588' => <<<'INPUT'
            NNCB
            
            CH -> B
            HH -> N
            CB -> H
            NH -> C
            HB -> C
            HC -> B
            HN -> C
            NN -> C
            BH -> H
            NC -> B
            NB -> B
            BN -> B
            BB -> N
            BC -> B
            CC -> N
            CN -> C
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '2188189693529' => <<<'INPUT'
            NNCB
            
            CH -> B
            HH -> N
            CB -> H
            NH -> C
            HB -> C
            HC -> B
            HN -> C
            NN -> C
            BH -> H
            NC -> B
            NB -> B
            BN -> B
            BB -> N
            BC -> B
            CC -> N
            CN -> C
            INPUT;
    }

    public function solvePart1(string $input): string
    {
        return (string) $this->solveForSteps($input, 10);
    }

    public function solvePart2(string $input): string
    {
        return (string) $this->solveForSteps($input, 40);
    }

    private function solveForSteps(string $input, int $steps): int
    {
        [$template, $insertions] = explode("\n\n", $input);
        $template = str_split($template);

        $between = [];

        foreach (explode("\n", $insertions) as $insertion) {
            [$pair, $character] = sscanf($insertion, '%s -> %s');
            $between[$pair] = $character;
        }

        $pairCounter = array_fill_keys(array_keys($between), 0);

        for ($i = 0; $i < count($template) - 1; $i++) {
            $pairCounter[$template[$i] . $template[$i + 1]]++;
        }

        for ($step = 1; $step <= $steps; $step++) {
            $tempPairCounter = $pairCounter;

            foreach ($pairCounter as $pair => $value) {
                $tempPairCounter[$pair] -= $value;
                $tempPairCounter[$pair[0] . $between[$pair]] += $value;
                $tempPairCounter[$between[$pair] . $pair[1]] += $value;
            }

            $pairCounter = $tempPairCounter;
        }

        // Increase first and last by one, so they are equally present twice as the other characters.
        $charCounter = [$template[0] => 1, $template[array_key_last($template)] => 1];

        foreach ($pairCounter as $pair => $count) {
            $charCounter[$pair[0]] = ($charCounter[$pair[0]] ?? 0) + $count;
            $charCounter[$pair[1]] = ($charCounter[$pair[1]] ?? 0) + $count;
        }

        return max($charCounter) / 2 - min($charCounter) / 2;
    }
}
