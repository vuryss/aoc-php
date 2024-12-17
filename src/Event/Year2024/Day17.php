<?php

declare(strict_types=1);

namespace App\Event\Year2024;

use App\Event\DayInterface;
use App\Util\StringUtil;

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
        [$a, $program] = $this->parseInput($input);

        return $this->execute($a, $program);
    }

    public function solvePart2(string $input): string|int
    {
        [, $program] = $this->parseInput($input);
        $search = implode(',', $program);
        $a = 0;
        $matchedLength = 1;

        while (true) {
            $output = $this->execute($a, $program);

            if (substr($search, -$matchedLength) === $output) {
                if ($search === $output) {
                    return $a;
                }

                $a <<= 3;
                $matchedLength += 2;

                continue;
            }

            $a++;
        }
    }

    private function parseInput(string $input): array
    {
        $blocks = explode("\n\n", $input);
        $blocks[1] = str_replace('Program: ', '', $blocks[1]);
        $program = array_map('intval', explode(',', $blocks[1]));
        $a = StringUtil::extractIntegers(explode("\n", $blocks[0])[0])[0];

        return [$a, $program];
    }

    private function execute(int $a, array $program): string
    {
        $registers = ['A' => $a, 'B' => 0, 'C' => 0];
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

        return implode(',', $output);
    }
}
