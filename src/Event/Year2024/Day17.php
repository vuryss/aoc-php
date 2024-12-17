<?php

declare(strict_types=1);

namespace App\Event\Year2024;

use App\Event\DayInterface;

class Day17 implements DayInterface
{
    public function testPart1(): iterable
    {
        yield '4,6,3,5,6,3,5,2,1,0' => <<<'INPUT'
            Register A: 729
            Register B: 0
            Register C: 0
            
            Program: 0,1,5,4,3,0
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '117440' => <<<'INPUT'
            Register A: 2024
            Register B: 0
            Register C: 0
            
            Program: 0,3,5,4,3,0
            INPUT;
    }

    public function solvePart1(string $input): string|int
    {
        [$registers, $program] = $this->parseInput($input);

        return implode(',', $this->execute($registers, $program));
    }

    public function solvePart2(string $input): string|int
    {
        [$registers, $program] = $this->parseInput($input);
        $search = array_reverse($program);
        $a = 0;
        $lastRemoved = null;

        while (true) {
            $registers['A'] = $a;
            $output = $this->execute($registers, $program);

            if ($output[0] === $search[0] && ($lastRemoved === null || $lastRemoved === $output[1])) {
                $lastRemoved = array_shift($search);

                if ([]  === $search) {
                    break;
                }

                $a <<= 3;

                continue;
            }

            $a++;
        }

        return $a;
    }

    private function parseInput(string $input): array
    {
        $blocks = explode("\n\n", $input);
        $registers = [];
        $blocks[1] = str_replace('Program: ', '', $blocks[1]);
        $program = array_map('intval', explode(',', $blocks[1]));

        foreach (explode("\n", $blocks[0]) as $line) {
            preg_match('/Register ([A-Z]): (\d+)/', $line, $matches);
            $registers[$matches[1]] = (int)$matches[2];
        }

        return [$registers, $program];
    }

    private function execute(array $registers, array $program): array
    {
        $output = [];
        $pointer = 0;

        while ($pointer < count($program)) {
            $instruction = $program[$pointer];
            $comboOperand = $program[$pointer + 1];
            $value = match (true) {
                $comboOperand <= 3 => $comboOperand,
                $comboOperand === 4 => $registers['A'],
                $comboOperand === 5 => $registers['B'],
                $comboOperand === 6 => $registers['C'],
            };

            match ($instruction) {
                0 => $registers['A'] = intdiv($registers['A'], 2 ** $value),
                1 => $registers['B'] ^= $comboOperand,
                2 => $registers['B'] = $value % 8,
                3 => $pointer = $registers['A'] !== 0 ? $comboOperand - 2 : $pointer,
                4 => $registers['B'] ^= $registers['C'],
                5 => $output[] = $value % 8,
                6 => $registers['B'] = intdiv($registers['A'], 2 ** $value),
                7 => $registers['C'] = intdiv($registers['A'], 2 ** $value),
            };

            $pointer += 2;
        }

        return $output;
    }
}
