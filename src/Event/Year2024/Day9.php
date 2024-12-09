<?php

declare(strict_types=1);

namespace App\Event\Year2024;

use App\Event\DayInterface;

class Day9 implements DayInterface
{
    public function testPart1(): iterable
    {
        yield '1928' => <<<'INPUT'
            2333133121414131402
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '2858' => <<<'INPUT'
            2333133121414131402
            INPUT;
    }

    public function solvePart1(string $input): string|int
    {
        $chars = str_split($input);
        $disk = [];
        $fileIndex = 0;

        foreach ($chars as $index => $digit) {
            array_push($disk, ...array_fill(0, (int) $digit, $index % 2 === 0 ? $fileIndex++ : null));
        }

        $sum = 0;
        $reverseIndex = count($disk) - 1;

        for ($i = 0; $i <= $reverseIndex; $i++) {
            $value = $disk[$i];

            if ($value === null) {
                for ($j = $reverseIndex; $j > $i; $j--) {
                    if ($disk[$j] !== null) {
                        $value = $disk[$j];
                        $reverseIndex = $j - 1;
                        break;
                    }
                }
            }

            $sum += $i * $value;
        }

        return $sum;
    }

    public function solvePart2(string $input): string|int
    {
        $chars = array_map(intval(...), str_split($input));
        $fileIndex = 0;
        $freeLocations = [];
        $blocks = [];
        $nextIndex = 0;

        foreach ($chars as $index => $digit) {
            $value = $index % 2 === 0 ? $fileIndex++ : null;
            $blocks[$nextIndex] = ['size' => $digit, 'value' => $value];

            if ($value === null) {
                $freeLocations[] = ['index' => $nextIndex, 'size' => $digit];
            }

            $nextIndex += $digit;
        }

        foreach (array_reverse($blocks, true) as $index => $data) {
            if ($data['value'] === null) {
                continue;
            }

            foreach ($freeLocations as $freeLocationIndex => ['size' => $size, 'index' => $location]) {
                if ($location > $index) {
                    break;
                }

                if ($size >= $data['size']) {
                    $blocks[$location] = $data;
                    $blocks[$index] = ['size' => $data['size'], 'value' => null];

                    if ($data['size'] < $size) {
                        $blocks[$location + $data['size']] = ['size' => $size - $data['size'], 'value' => null];
                        $freeLocations[$freeLocationIndex] = ['index' => $location + $data['size'], 'size' => $size - $data['size']];
                    } else {
                        unset($freeLocations[$freeLocationIndex]);
                    }

                    break;
                }
            }
        }

        $sum = 0;
        $index = 0;
        ksort($blocks);

        foreach ($blocks as $block) {
            for ($i = 0; $i < $block['size']; $i++) {
                $sum += $index++ * $block['value'];
            }
        }

        return $sum;
    }
}
