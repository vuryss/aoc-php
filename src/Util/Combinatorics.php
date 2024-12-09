<?php

declare(strict_types=1);

namespace App\Util;

readonly class Combinatorics
{
    /**
     * Produces all possible permutations of a given length from a set of elements. Without repetition.
     *
     * Example without length:
     * permutations([1, 2, 3]) => [[1, 2, 3], [1, 3, 2], [2, 1, 3], [2, 3, 1], [3, 1, 2], [3, 2, 1]]
     *
     * Example with length:
     * permutations([1, 2, 3], 2) => [[1, 2], [1, 3], [2, 1], [2, 3], [3, 1], [3, 2]]
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
