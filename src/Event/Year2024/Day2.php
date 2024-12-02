<?php

declare(strict_types=1);

namespace App\Event\Year2024;

use App\Event\DayInterface;
use App\Util\StringUtil;

class Day2 implements DayInterface
{
    public function testPart1(): iterable
    {
        yield '2' => <<<'INPUT'
            7 6 4 2 1
            1 2 7 8 9
            9 7 6 2 1
            1 3 2 4 5
            8 6 4 4 1
            1 3 6 7 9
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '4' => <<<'INPUT'
            7 6 4 2 1
            1 2 7 8 9
            9 7 6 2 1
            1 3 2 4 5
            8 6 4 4 1
            1 3 6 7 9
            INPUT;
    }

    public function solvePart1(string $input): string|int
    {
        $numberLines = array_map(StringUtil::extractIntegers(...), explode("\n", $input));

        return count(array_filter($numberLines, $this->isSafe(...)));
    }

    public function solvePart2(string $input): string|int
    {
        $numberLines = array_map(StringUtil::extractIntegers(...), explode("\n", $input));
        $count = 0;

        foreach ($numberLines as $numbers) {
            if ($this->isSafe($numbers)) {
                $count++;

                continue;
            }

            for ($i = 0; $i < count($numbers); $i++) {
                $newNumbers = array_diff_key($numbers, [$i => true]);

                if ($this->isSafe(array_values($newNumbers))) {
                    $count++;
                    break;
                }
            }
        }

        return $count;
    }

    private function isSafe(array $numbers): bool
    {
        $allIncreasing = true;
        $allDecreasing = true;
        $maxDifference = 0;
        $minDifference = PHP_INT_MAX;

        for ($i = 1; $i < count($numbers); $i++) {
            $difference = $numbers[$i] - $numbers[$i - 1];
            $maxDifference = max($maxDifference, abs($difference));
            $minDifference = min($minDifference, abs($difference));
            $allIncreasing = !($allIncreasing === false) && $difference > 0;
            $allDecreasing = !($allDecreasing === false) && $difference < 0;
        }

        return ($allIncreasing === true || $allDecreasing === true) && $maxDifference <= 3 && $minDifference >= 1;
    }
}
