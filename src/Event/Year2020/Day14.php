<?php

declare(strict_types=1);

namespace App\Event\Year2020;

use App\Event\DayInterface;

class Day14 implements DayInterface
{
    public function testPart1(): iterable
    {
        yield '165' => <<<'INPUT'
            mask = XXXXXXXXXXXXXXXXXXXXXXXXXXXXX1XXXX0X
            mem[8] = 11
            mem[7] = 101
            mem[8] = 0
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '208' => <<<'INPUT'
            mask = 000000000000000000000000000000X1001X
            mem[42] = 100
            mask = 00000000000000000000000000000000X0XX
            mem[26] = 1
            INPUT;
    }

    public function solvePart1(string $input): string
    {
        $lines = explode("\n", $input);

        $memory = [];
        $mask = [];

        foreach ($lines as $line) {
            if (1 === preg_match('/mask\s=\s([X01]+)/', $line, $matches)) {
                $mask = array_filter(str_split($matches[1]), static fn (string $char) => 'X' !== $char);
                continue;
            }

            preg_match('/mem\[(\d+)]\s=\s(\d+)/', $line, $matches);
            [, $address, $value] = $matches;
            $value = str_pad(decbin((int) $value), 36, '0', STR_PAD_LEFT);

            foreach ($mask as $index => $replacement) {
                $value[$index] = $replacement;
            }

            $memory[$address] = bindec($value);
        }

        return (string) array_sum($memory);
    }

    public function solvePart2(string $input): string
    {
        $lines = explode("\n", $input);

        $memory = [];
        $mask = [];

        foreach ($lines as $line) {
            if (1 === preg_match('/mask\s=\s([X01]+)/', $line, $matches)) {
                $mask = str_split($matches[1]);
                continue;
            }

            preg_match('/mem\[(\d+)]\s=\s(\d+)/', $line, $matches);
            [, $address, $value] = $matches;
            $address = str_pad(decbin((int) $address), 36, '0', STR_PAD_LEFT);

            foreach ($mask as $index => $replacement) {
                if ('1' === $replacement) {
                    $address[$index] = '1';
                }
            }

            $allAddresses = [$address];

            foreach ($mask as $index => $replacement) {
                if ('X' === $replacement) {
                    $current = $allAddresses;
                    $allAddresses = [];

                    foreach ($current as $address) {
                        $address[$index] = '0';
                        $allAddresses[] = $address;
                        $address[$index] = '1';
                        $allAddresses[] = $address;
                    }
                }
            }

            foreach ($allAddresses as $address) {
                $memory[(int) bindec($address)] = (int) $value;
            }
        }

        return (string) array_sum($memory);
    }
}
