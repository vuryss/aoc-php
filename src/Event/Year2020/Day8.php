<?php

declare(strict_types=1);

namespace App\Event\Year2020;

use App\Event\DayInterface;
use Exception;

class Day8 implements DayInterface
{
    public function testPart1(): iterable
    {
        yield '5' => <<<'INPUT'
            nop +0
            acc +1
            jmp +4
            acc +3
            jmp -3
            acc -99
            acc +1
            jmp -4
            acc +6
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '8' => <<<'INPUT'
            nop +0
            acc +1
            jmp +4
            acc +3
            jmp -3
            acc -99
            acc +1
            jmp -4
            acc +6
            INPUT;
    }

    public function solvePart1(string $input): string
    {
        $instructions = $this->parseInstructions($input);
        return (string) $this->runProgram($instructions);
    }

    public function solvePart2(string $input): string
    {
        $instructions = $this->parseInstructions($input);
        $visited = [];
        $this->runProgram($instructions, $visited);

        $position = $this->reverseEngineerIncorrectInstruction($instructions, $visited);

        $instructions[$position][0] = $instructions[$position][0] === 'jmp' ? 'nop' : 'jmp';

        return (string) $this->runProgram($instructions);
    }

    private function parseInstructions(string $input): array
    {
        $lines = explode("\n", $input);
        $instructions = [];

        foreach ($lines as $line) {
            $instructions[] = explode(' ', $line);
        }

        return $instructions;
    }

    private function runProgram(array $instructions, array &$visited = []): int
    {
        $instructionsCount = count($instructions);
        $accumulator = 0;
        $position = 0;

        while ($position < $instructionsCount) {
            $currentPosition = $position;

            switch ($instructions[$position][0]) {
                case 'nop':
                    $position++;
                    break;

                case 'acc':
                    $accumulator += (int) $instructions[$position][1];
                    $position++;
                    break;

                case 'jmp':
                    $position += (int) $instructions[$position][1];
                    break;
            }

            if (isset($visited[$position])) {
                break;
            }

            $visited[$currentPosition] = true;
        }

        return $accumulator;
    }

    /**
     * @param array<int, array<int, string>> $instructions
     * @param array<int, bool> $visited
     *
     * @return int
     * @throws Exception
     */
    private function reverseEngineerIncorrectInstruction(array $instructions, array $visited): int
    {
        $queue = [count($instructions)];

        do {
            $max = $min = array_shift($queue);

            for ($i = $max - 1; $i >= 0; $i--) {
                if ($instructions[$i][0] === 'jmp') {
                    if ($instructions[$i][1] > 0 && $instructions[$i][1] + $i > $max) {
                        if (isset($visited[$i])) {
                            return $i;
                        }
                        break;
                    }
                    if ($instructions[$i][1] <= 0) {
                        if (isset($visited[$i])) {
                            return $i;
                        }
                        break;
                    }
                }

                $min = $i;
            }

            foreach ($instructions as $position => $instruction) {
                if ($position >= $min && $position <= $max || $instruction[0] === 'acc') {
                    continue;
                }

                $newPosition = $position + $instruction[1];

                if ($newPosition >= $min && $newPosition <= $max) {
                    $queue[] = $position;
                }
            }
        } while (count($queue) > 0);

        throw new Exception('Position not found');
    }
}
