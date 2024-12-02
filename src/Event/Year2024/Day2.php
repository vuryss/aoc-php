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
        return count(array_filter(
            array_map(StringUtil::extractIntegers(...), explode("\n", $input)),
            $this->isSafe(...))
        );
    }

    public function solvePart2(string $input): string|int
    {
        return count(array_filter(
            array_map(StringUtil::extractIntegers(...), explode("\n", $input)),
            fn ($numbers) => array_any(
                range(0, count($numbers) - 1),
                fn ($i) => $this->isSafe(array_values(array_diff_key($numbers, [$i => true])))
            )
        ));
    }

    private function isSafe(array $numbers): bool
    {
        return array_any([$numbers, array_reverse($numbers)], fn ($numbers) => !array_any(
            range(1, count($numbers) - 1),
            fn ($i) => !in_array($numbers[$i] - $numbers[$i - 1], [1, 2, 3])
        ));
    }
}
