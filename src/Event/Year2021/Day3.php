<?php

declare(strict_types=1);

namespace App\Event\Year2021;

use App\Event\DayInterface;

class Day3 implements DayInterface
{
    public function testPart1(): iterable
    {
        yield '198' => <<<'INPUT'
            00100
            11110
            10110
            10111
            10101
            01111
            00111
            11100
            10000
            11001
            00010
            01010
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '230' => <<<'INPUT'
            00100
            11110
            10110
            10111
            10101
            01111
            00111
            11100
            10000
            11001
            00010
            01010
            INPUT;
    }

    public function solvePart1(string $input): string
    {
        $binaryNumbers = explode("\n", $input);
        $bits = $this->calculateBits($binaryNumbers);
        $gamma = array_reduce(
            $bits,
            static fn ($carry, $item) => $carry . ($item[1] > $item[0] ? '1' : '0'),
            ''
        );
        $eps = strtr($gamma, '01', '10');

        return (string) (bindec($gamma) * bindec($eps));
    }

    public function solvePart2(string $input): string
    {
        $binaryNumbers = explode("\n", $input);

        $oxi = $this->reduceNumbers($binaryNumbers, '1', '0');
        $co2 = $this->reduceNumbers($binaryNumbers, '0', '1');

        return (string) (bindec($oxi) * bindec($co2));
    }

    private function calculateBits(array $binaryNumbers): array
    {
        $bits = [];

        foreach ($binaryNumbers as $line) {
            foreach (str_split($line) as $index => $char) {
                $bits[$index][$char] = ($bits[$index][$char] ?? 0) + 1;
            }
        }

        return $bits;
    }

    private function reduceNumbers(array $binaryNumbers, string $highBit, string $lowBit): string
    {
        $bitIndex = 0;

        while (count($binaryNumbers) > 1) {
            $bit = $this->calculateBits($binaryNumbers)[$bitIndex];

            $binaryNumbers = array_filter(
                $binaryNumbers,
                static fn ($n): bool => $n[$bitIndex] === ($bit[1] >= $bit[0] ? $highBit : $lowBit)
            );

            $bitIndex++;
        }

        return current($binaryNumbers);
    }
}
