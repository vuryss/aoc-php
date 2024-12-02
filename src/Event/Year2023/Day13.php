<?php

declare(strict_types=1);

namespace App\Event\Year2023;

use App\Event\DayInterface;

class Day13 implements DayInterface
{
    public function testPart1(): iterable
    {
        yield '405' => <<<'INPUT'
            #.##..##.
            ..#.##.#.
            ##......#
            ##......#
            ..#.##.#.
            ..##..##.
            #.#.##.#.
            
            #...##..#
            #....#..#
            ..##..###
            #####.##.
            #####.##.
            ..##..###
            #....#..#
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '400' => <<<'INPUT'
            #.##..##.
            ..#.##.#.
            ##......#
            ##......#
            ..#.##.#.
            ..##..##.
            #.#.##.#.
            
            #...##..#
            #....#..#
            ..##..###
            #####.##.
            #####.##.
            ..##..###
            #....#..#
            INPUT;
    }

    public function solvePart1(string $input): string|int
    {
        $patterns = explode("\n\n", $input);
        $sum = 0;

        foreach ($patterns as $pattern) {
            $lines = explode("\n", $pattern);
            $columns = $this->extractColumns($lines);
            $sum += $this->findReflection($columns) ?? 100 * $this->findReflection($lines);
        }

        return $sum;
    }

    public function solvePart2(string $input): string|int
    {
        $patterns = explode("\n\n", $input);
        $sum = 0;

        foreach ($patterns as $pattern) {
            $lines = explode("\n", $pattern);
            $columns = $this->extractColumns($lines);
            $sum += $this->findReflectionWithSmudge($columns) ?? 100 * $this->findReflectionWithSmudge($lines);
        }

        return $sum;
    }

    private function extractColumns(array $lines): array
    {
        $columns = [];

        for ($i = 0, $max = strlen($lines[0]); $i < $max; $i++) {
            foreach ($lines as $line) {
                $columns[$i][] = $line[$i];
            }

            $columns[$i] = implode('', $columns[$i]);
        }

        return $columns;
    }

    private function findReflection(array $lines): ?int
    {
        for ($i = 1; $i < count($lines); $i++) {
            $diff = 0;

            do {
                if (!isset($lines[$i - $diff - 1], $lines[$i + $diff])) {
                    return $i;
                }

                if ($lines[$i - $diff - 1] !== $lines[$i + $diff]) {
                    continue 2;
                }
            } while (++$diff);
        }

        return null;
    }

    private function findReflectionWithSmudge(array $lines): ?int
    {
        for ($i = 1; $i < count($lines); $i++) {
            $diff = 0;
            $distance = 0;

            do {
                if (!isset($lines[$i - $diff - 1], $lines[$i + $diff])) {
                    if (1 === $distance) {
                        return $i;
                    }

                    continue 2;
                }

                $distance += levenshtein($lines[$i - $diff - 1], $lines[$i + $diff]);
            } while (++$diff);
        }

        return null;
    }
}
