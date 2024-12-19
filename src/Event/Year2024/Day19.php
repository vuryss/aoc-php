<?php

declare(strict_types=1);

namespace App\Event\Year2024;

use App\Event\DayInterface;

class Day19 implements DayInterface
{
    private array $cache = [];

    public function testPart1(): iterable
    {
        yield '6' => <<<'INPUT'
            r, wr, b, g, bwu, rb, gb, br
            
            brwrr
            bggr
            gbbr
            rrbgbr
            ubwu
            bwurrg
            brgr
            bbrgwb
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '16' => <<<'INPUT'
            r, wr, b, g, bwu, rb, gb, br
            
            brwrr
            bggr
            gbbr
            rrbgbr
            ubwu
            bwurrg
            brgr
            bbrgwb
            INPUT;
    }

    public function solvePart1(string $input): string|int
    {
        $blocks = explode("\n\n", $input);
        $towels = explode(', ', array_shift($blocks));
        $required = explode("\n", $blocks[0]);

        return count(array_filter($required, fn ($item) => $this->totalCombinations($towels, $item) > 0));
    }

    public function solvePart2(string $input): string|int
    {
        $blocks = explode("\n\n", $input);
        $towels = explode(', ', array_shift($blocks));
        $required = explode("\n", $blocks[0]);
        $this->cache = [];

        return array_sum(array_map(fn ($item) => $this->totalCombinations($towels, $item), $required));
    }

    private function totalCombinations(array $towels, string $item): int
    {
        if (isset($this->cache[$item])) {
            return $this->cache[$item];
        }

        $count = 0;

        foreach ($towels as $towel) {
            if (str_starts_with($item, $towel)) {
                $rest = substr($item, strlen($towel));
                $count += $rest === '' ? 1 : $this->totalCombinations($towels, $rest);
            }
        }

        return $this->cache[$item] = $count;
    }
}
