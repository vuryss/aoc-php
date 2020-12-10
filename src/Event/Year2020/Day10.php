<?php

declare(strict_types=1);

namespace App\Event\Year2020;

use App\Event\DayInterface;

class Day10 implements DayInterface
{
    public function testPart1(): iterable
    {
        yield '35' => <<<'INPUT'
            16
            10
            15
            5
            1
            11
            7
            19
            6
            12
            4
            INPUT;

        yield '220' => <<<'INPUT'
            28
            33
            18
            42
            31
            14
            46
            20
            48
            47
            24
            23
            49
            45
            19
            38
            39
            11
            1
            32
            25
            35
            8
            17
            7
            9
            4
            2
            34
            10
            3
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '8' => <<<'INPUT'
            16
            10
            15
            5
            1
            11
            7
            19
            6
            12
            4
            INPUT;

        yield '19208' => <<<'INPUT'
            28
            33
            18
            42
            31
            14
            46
            20
            48
            47
            24
            23
            49
            45
            19
            38
            39
            11
            1
            32
            25
            35
            8
            17
            7
            9
            4
            2
            34
            10
            3
            INPUT;
    }

    public function solvePart1(string $input): string
    {
        $adapters = array_map('intval', explode("\n", $input));
        sort($adapters);
        $ones = 0;
        $threes = 1;

        for ($i = 0, $max = count($adapters); $i < $max; $i++) {
            $diff = $adapters[$i] - ($adapters[$i - 1] ?? 0);

            if ($diff === 1) {
                $ones++;
            } elseif ($diff === 3) {
                $threes++;
            }
        }

        return (string) ($ones * $threes);
    }

    public function solvePart2(string $input): string
    {
        $adapters = array_map('intval', explode("\n", $input));
        $deviceJolts = max($adapters) + 3;
        $adapterMap = array_fill_keys($adapters, 0);
        $adapterMap[0] = 1;
        $adapterMap[$deviceJolts] = 0;
        ksort($adapterMap);

        foreach ($adapterMap as $key => $value) {
            if ($key === 0) continue;

            $combinations = 0;

            for ($i = 1; $i <= 3; $i++) {
                if (isset($adapterMap[$key - $i])) {
                    $combinations += $adapterMap[$key - $i];
                }
            }

            $adapterMap[$key] = $combinations;
        }

        return (string) $adapterMap[$deviceJolts];
    }
}
