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
        $gamma = '';

        foreach ($bits as $bit) {
            $gamma .= $bit[1] > $bit[0] ? '1' : '0';
        }

        $eps = strtr($gamma, ['0' => '1', '1' => '0']);

        return (string) ((int) base_convert($gamma, 2, 10) * (int) base_convert($eps, 2, 10));
    }

    public function solvePart2(string $input): string
    {
        $binaryNumbers = explode("\n", $input);

        $filteredNumbers = $binaryNumbers;
        $bitIndex = 0;

        while (count($filteredNumbers) > 1) {
            $bit = $this->calculateBits($filteredNumbers)[$bitIndex];

            $filteredNumbers = array_filter(
                $filteredNumbers,
                static fn ($n): bool => $n[$bitIndex] === ($bit[1] >= $bit[0] ? '1' : '0')
            );

            $bitIndex++;
        }

        $oxi = current($filteredNumbers);

        $filteredNumbers = $binaryNumbers;
        $bitIndex = 0;

        while (count($filteredNumbers) > 1) {
            $bit = $this->calculateBits($filteredNumbers)[$bitIndex];

            $filteredNumbers = array_filter(
                $filteredNumbers,
                static fn ($n): bool => $n[$bitIndex] === ($bit[1] >= $bit[0] ? '0' : '1')
            );

            $bitIndex++;
        }

        $co2 = current($filteredNumbers);

        return (string) ((int) base_convert($oxi, 2, 10) * (int) base_convert($co2, 2, 10));
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
}
