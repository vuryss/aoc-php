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
        $chars = array_map(intval(...), str_split($input));
        $disk = [];
        $fileIndex = 0;

        foreach ($chars as $index => $digit) {
            if ($index % 2 === 0) {
                array_push($disk, ...array_fill(0, $digit, $fileIndex++));
            } else {
                array_push($disk, ...array_fill(0, $digit, null));
            }
        }

        for ($i = count($disk) - 1; $i >= 0; $i--) {
            if ($disk[$i] === null) {
                continue;
            }

            for ($j = 0; $j < count($disk); $j++) {
                if ($j === $i) {
                    break 2;
                }

                if ($disk[$j] === null) {
                    $disk[$j] = $disk[$i];
                    $disk[$i] = null;
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
