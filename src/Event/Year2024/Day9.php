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
        $disk = [];
        $fileIndex = 0;
        $blockLocations = [];
        $blockSizes = [];
        $freeLocations = [];

        foreach ($chars as $index => $digit) {
            if ($index % 2 === 0) {
                $blockLocations[$fileIndex] = count($disk);
                $blockSizes[$fileIndex] = $digit;
                array_push($disk, ...array_fill(0, $digit, $fileIndex));
                $fileIndex++;
            } else {
                $freeLocations[count($disk)] = $digit;
                array_push($disk, ...array_fill(0, $digit, null));
            }
        }

        for ($fileIndex = count($blockLocations) - 1; $fileIndex > 0; $fileIndex--) {
            foreach ($freeLocations as $index => $size) {
                if ($index > $blockLocations[$fileIndex]) {
                    break;
                }

                if ($size >= $blockSizes[$fileIndex]) {
                    array_splice($disk, $index, $blockSizes[$fileIndex], array_fill(0, $blockSizes[$fileIndex], $fileIndex));
                    array_splice($disk, $blockLocations[$fileIndex], $blockSizes[$fileIndex], array_fill(0, $blockSizes[$fileIndex], null));
                    unset($freeLocations[$index]);
                    if ($blockSizes[$fileIndex] < $size) {
                        $freeLocations[$index + $blockSizes[$fileIndex]] = $size - $blockSizes[$fileIndex];
                    }
                    $freeLocations[$blockLocations[$fileIndex]] = $blockSizes[$fileIndex];
                    ksort($freeLocations);
                    break;
                }
            }
        }

        $sum = 0;

        foreach ($disk as $index => $fileIndex) {
            $sum += $index * $fileIndex;
        }

        return $sum;
    }
}
