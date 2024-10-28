<?php

declare(strict_types=1);

namespace App\Event\Year2023;

use App\Event\DayInterface;
use App\Util\StringUtil;

class Day5 implements DayInterface
{
    public function testPart1(): iterable
    {
        yield '35' => <<<'INPUT'
            seeds: 79 14 55 13
            
            seed-to-soil map:
            50 98 2
            52 50 48
            
            soil-to-fertilizer map:
            0 15 37
            37 52 2
            39 0 15
            
            fertilizer-to-water map:
            49 53 8
            0 11 42
            42 0 7
            57 7 4
            
            water-to-light map:
            88 18 7
            18 25 70
            
            light-to-temperature map:
            45 77 23
            81 45 19
            68 64 13
            
            temperature-to-humidity map:
            0 69 1
            1 0 69
            
            humidity-to-location map:
            60 56 37
            56 93 4
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '46' => <<<'INPUT'
            seeds: 79 14 55 13
            
            seed-to-soil map:
            50 98 2
            52 50 48
            
            soil-to-fertilizer map:
            0 15 37
            37 52 2
            39 0 15
            
            fertilizer-to-water map:
            49 53 8
            0 11 42
            42 0 7
            57 7 4
            
            water-to-light map:
            88 18 7
            18 25 70
            
            light-to-temperature map:
            45 77 23
            81 45 19
            68 64 13
            
            temperature-to-humidity map:
            0 69 1
            1 0 69
            
            humidity-to-location map:
            60 56 37
            56 93 4
            INPUT;
    }

    public function solvePart1(string $input): string|int
    {
        [$seeds, $mapCollection] = $this->parseInput($input);

        foreach ($mapCollection as $mapRanges) {
            foreach ($seeds as $key => $value) {
                foreach ($mapRanges as $map) {
                    if ($value >= $map['sourceStart'] && $value <= $map['sourceEnd']) {
                        $seeds[$key] = $map['destinationStart'] + ($value - $map['sourceStart']);
                        break;
                    }
                }
            }
        }

        return min($seeds);
    }

    public function solvePart2(string $input): string|int
    {
        [$seeds, $mapCollection] = $this->parseInput($input);

        $ranges = [];

        for ($i = 0; $i < count($seeds); $i += 2) {
            $ranges[] = ['start' => $seeds[$i], 'end' => $seeds[$i] + $seeds[$i + 1] - 1];
        }

        $newRanges = $ranges;

        foreach ($mapCollection as $mapRanges) {
            $ranges = $newRanges;
            $newRanges = [];

            foreach ($ranges as $range) {
                $unmappedRanges = [$range];

                foreach ($mapRanges as $map) {
                    foreach ($unmappedRanges as $key => $unmappedRange) {
                        $intersectionStart = max($unmappedRange['start'], $map['sourceStart']);
                        $intersectionEnd = min($unmappedRange['end'], $map['sourceEnd']);

                        if ($intersectionStart > $intersectionEnd) {
                            continue;
                        }

                        unset($unmappedRanges[$key]);

                        $newRanges[] = [
                            'start' => $map['destinationStart'] + ($intersectionStart - $map['sourceStart']),
                            'end' => $map['destinationStart'] + ($intersectionEnd - $map['sourceStart']),
                        ];

                        if ($unmappedRange['start'] < $intersectionStart) {
                            $unmappedRanges[] = ['start' => $unmappedRange['start'], 'end' => $intersectionStart - 1];
                        }

                        if ($unmappedRange['end'] > $intersectionEnd) {
                            $unmappedRanges[] = ['start' => $intersectionEnd + 1, 'end' => $unmappedRange['end']];
                        }
                    }
                }

                $newRanges = array_merge($newRanges, $unmappedRanges);
            }
        }

        return min(array_column($newRanges, 'start'));
    }

    public function parseInput(string $input): array
    {
        $parts = explode("\n\n", $input);
        $seeds = StringUtil::extractIntegers($parts[0]);
        array_shift($parts);

        $maps = [];

        foreach ($parts as $part) {
            $lines = explode("\n", $part);
            array_shift($lines);
            $map = [];

            foreach ($lines as $line) {
                $values = StringUtil::extractIntegers($line);
                $map[] = [
                    'sourceStart' => $values[1],
                    'sourceEnd' => $values[1] + $values[2] - 1,
                    'destinationStart' => $values[0],
                    'destinationEnd' => $values[0] + $values[2] - 1,
                ];
            }

            $maps[] = $map;
        }

        return [$seeds, $maps];
    }
}
