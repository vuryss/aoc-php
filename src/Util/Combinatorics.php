<?php

declare(strict_types=1);

namespace App\Util;

readonly class Combinatorics
{
    /**
     * Produces all possible unique permutations of a set of elements, which may include duplicates.
     *
     * @return iterable<array>
     */
    public static function permutationsOfDuplicates(array $elements): iterable
    {
        yield from self::permutations2($elements);
    }

    private static function permutations2(array $elements, array $used = [], int $index = 0): iterable
    {
        $length = count($elements);

        if ($length === 0) {
            yield [];
            return;
        }

        foreach ($elements as $key => $element) {
            if (isset($used[$index][$element])) {
                continue;
            }
            $used[$index][$element] = true;
            $remaining = $elements;
            unset($remaining[$key]);

            foreach (self::permutations2($remaining, $used, $index + 1) as $permutation) {
                yield [$element, ...$permutation];
            }
        }
    }

    /**
     * Produces all possible permutations of a given length from a set of elements which does not include duplicates.
     *
     * Example without length:
     * permutations([1, 2, 3]) => [[1, 2, 3], [1, 3, 2], [2, 1, 3], [2, 3, 1], [3, 1, 2], [3, 2, 1]]
     *
     * Example with length:
     * permutations([1, 2, 3], 2) => [[1, 2], [1, 3], [2, 1], [2, 3], [3, 1], [3, 2]]
     *
     * @return iterable<array>
     */
    public static function permutations(array $elements, ?int $length = null): iterable
    {
        $length ??= count($elements);

        if ($length === 0) {
            yield [];

            return;
        }

        foreach ($elements as $key => $element) {
            $remaining = $elements;
            unset($remaining[$key]);

            foreach (self::permutations($remaining, $length - 1) as $permutation) {
                yield [$element, ...$permutation];
            }
        }
    }

    /**
     * Produces all possible combinations of a given length from a set of elements. Without repetition.
     *
     * Example: combinations([1, 2, 3], 2) => [[1, 2], [1, 3], [2, 3]]
     *
     * @return iterable<array>
     */
    public static function combinations(array $elements, int $length): iterable
    {
        $dataLength = count($elements);
        $remainingLength = $dataLength - $length + 1;

        if ($length === 1) {
            yield from array_chunk($elements, 1);
            return;
        }

        for ($i = 0; $i < $remainingLength; ++$i) {
            $prefix = $elements[$i];
            $remaining = array_slice($elements, $i + 1);

            foreach (self::combinations($remaining, $length - 1) as $combination) {
                yield [$prefix, ...$combination];
            }
        }
    }
}
