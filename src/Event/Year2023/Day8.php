<?php

declare(strict_types=1);

namespace App\Event\Year2023;

use App\Event\DayInterface;

class Day8 implements DayInterface
{
    public function testPart1(): iterable
    {
        yield '2' => <<<'INPUT'
            RL
            
            AAA = (BBB, CCC)
            BBB = (DDD, EEE)
            CCC = (ZZZ, GGG)
            DDD = (DDD, DDD)
            EEE = (EEE, EEE)
            GGG = (GGG, GGG)
            ZZZ = (ZZZ, ZZZ)
            INPUT;

        yield '6' => <<<'INPUT'
            LLR
            
            AAA = (BBB, BBB)
            BBB = (AAA, ZZZ)
            ZZZ = (ZZZ, ZZZ)
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '6' => <<<'INPUT'
            LR
            
            11A = (11B, XXX)
            11B = (XXX, 11Z)
            11Z = (11B, XXX)
            22A = (22B, XXX)
            22B = (22C, 22C)
            22C = (22Z, 22Z)
            22Z = (22B, 22B)
            XXX = (XXX, XXX)
            INPUT;
    }

    public function solvePart1(string $input): string|int
    {
        $parts = explode("\n\n", $input);
        $instructions = str_split(trim($parts[0]));
        $map = [];

        foreach (explode("\n", $parts[1]) as $line) {
            preg_match('/^(\w+) = \((\w+), (\w+)\)$/', $line, $matches);
            $map[$matches[1]] = ['L' => $matches[2], 'R' => $matches[3]];
        }

        $node = 'AAA';
        $steps = 0;
        $instructionPointer = 0;

        while ('ZZZ' !== $node) {
            $node = $map[$node][$instructions[$instructionPointer]];
            $steps++;
            $instructionPointer = ($instructionPointer + 1) % count($instructions);
        }

        return $steps;
    }

    public function solvePart2(string $input): string|int
    {
        $parts = explode("\n\n", $input);
        $instructions = str_split(trim($parts[0]));
        $ghosts = [];
        $stepsLoopPoint = [];
        $map = [];

        foreach (explode("\n", $parts[1]) as $line) {
            preg_match('/^([0-9A-Z]+) = \(([0-9A-Z]+), ([0-9A-Z]+)\)$/', $line, $matches);
            $map[$matches[1]] = ['L' => $matches[2], 'R' => $matches[3]];

            if (str_ends_with($matches[1], 'A')) {
                $ghosts[] = $matches[1];
            }
        }

        $steps = 0;

        while (count($stepsLoopPoint) < count($ghosts)) {
            $instruction = $instructions[$steps % count($instructions)];
            $newGhosts = array_map(static fn (string $node): string => $map[$node][$instruction], $ghosts);
            $steps++;

            foreach ($newGhosts as $key => $ghost) {
                if (!isset($stepsLoopPoint[$key]) && str_ends_with($ghost, 'Z')) {
                    $stepsLoopPoint[$key] = $steps;
                }
            }

            $ghosts = $newGhosts;
        }

        $lcm = $stepsLoopPoint[0];

        for ($i = 1; $i < count($ghosts); $i++) {
            $lcm = gmp_lcm($lcm, $stepsLoopPoint[$i]);
        }

        return gmp_intval($lcm);
    }
}
