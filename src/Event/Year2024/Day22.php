<?php

declare(strict_types=1);

namespace App\Event\Year2024;

use App\Event\DayInterface;

class Day22 implements DayInterface
{
    public function testPart1(): iterable
    {
        yield '37327623' => <<<'INPUT'
            1
            10
            100
            2024
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '23' => <<<'INPUT'
            1
            2
            3
            2024
            INPUT;
    }

    public function solvePart1(string $input): string|int
    {
        $numbers = explode("\n", $input);
        $sum = 0;

        foreach ($numbers as $number) {
            for ($i = 0; $i < 2000; $i++) {
                $number = $this->generateSecretNumber((int) $number);
            }

            $sum += $number;
        }

        return $sum;
    }

    public function solvePart2(string $input): string|int
    {
        $numbers = explode("\n", $input);
        $sequences = [];

        foreach ($numbers as $index => $number) {
            $sequence = [];
            $lastNumber = $number % 10;

            for ($i = 0; $i < 2000; $i++) {
                $number = $this->generateSecretNumber((int) $number);
                $newLastNumber = $number % 10;
                $sequence[] = $newLastNumber - $lastNumber;
                $lastNumber = $newLastNumber;

                if (count($sequence) === 4) {
                    $key = implode(',', $sequence);
                    $sequences[$key][$index] ??= $lastNumber;
                    array_shift($sequence);
                }
            }
        }

        return max(array_map(fn ($list) => array_sum($list), $sequences));
    }

    private function generateSecretNumber(int $number): int
    {
        $number = ($number << 6 ^ $number) & 16777215;
        $number = ($number >> 5 ^ $number) & 16777215;

        return ($number << 11 ^ $number) & 16777215;
    }
}
