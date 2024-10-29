<?php

declare(strict_types=1);

namespace App\Event\Year2023;

use App\Event\DayInterface;
use App\Util\StringUtil;

class Day6 implements DayInterface
{
    public function testPart1(): iterable
    {
        yield '288' => <<<'INPUT'
            Time:      7  15   30
            Distance:  9  40  200
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '71503' => <<<'INPUT'
            Time:      7  15   30
            Distance:  9  40  200
            INPUT;
    }

    public function solvePart1(string $input): string|int
    {
        $lines = explode("\n", $input);
        $milliseconds = StringUtil::extractIntegers($lines[0]);
        $distances = StringUtil::extractIntegers($lines[1]);
        $product = 1;

        for ($i = 0; $i < count($milliseconds); $i++) {
            $ways = 0;
            for ($ms = 1; $ms < $milliseconds[$i]; $ms++) {
                $distance = $ms * ($milliseconds[$i] - $ms);

                if ($distance > $distances[$i]) {
                    $ways++;
                }
            }
            $product *= $ways;
        }

        return $product;
    }

    public function solvePart2(string $input): string|int
    {
        $lines = explode("\n", $input);
        $milliseconds = (int) implode('', StringUtil::extractIntegers($lines[0]));
        $record = (int) implode('', StringUtil::extractIntegers($lines[1]));

        for ($ms = 1; $ms < $milliseconds; $ms++) {
            $distance = $ms * ($milliseconds - $ms);

            if ($distance > $record) {
                // From total available combinations (milliseconds + 1 to include 0) we need to subtract the ones that
                // are invalid, which is an equal number from the beginning and the end of the range.
                return ($milliseconds + 1) - (2 * $ms);
            }
        }

        return 0;
    }
}
