<?php

declare(strict_types=1);

namespace App\Event\Year2023;

use App\Event\DayInterface;
use App\Util\StringUtil;

class Day12 implements DayInterface
{
    private array $memory = [];

    public function testPart1(): iterable
    {
        yield '21' => <<<'INPUT'
            ???.### 1,1,3
            .??..??...?##. 1,1,3
            ?#?#?#?#?#?#?#? 1,3,1,6
            ????.#...#... 4,1,1
            ????.######..#####. 1,6,5
            ?###???????? 3,2,1
            INPUT;

        yield '11' => <<<'INPUT'
            ??.????.????.?#..? 1,1
            INPUT;

    }

    public function testPart2(): iterable
    {
        yield '525152' => <<<'INPUT'
            ???.### 1,1,3
            .??..??...?##. 1,1,3
            ?#?#?#?#?#?#?#? 1,3,1,6
            ????.#...#... 4,1,1
            ????.######..#####. 1,6,5
            ?###???????? 3,2,1
            INPUT;
    }

    public function solvePart1(string $input): string|int
    {
        $sum = 0;

        foreach (explode("\n", $input) as $line) {
            [$line, $counts] = explode(' ', $line);
            $counts = StringUtil::extractIntegers($counts);

            $sum += $this->combinations($line, $counts);
        }

        return $sum;
    }

    public function solvePart2(string $input): string|int
    {
        $sum = 0;

        foreach (explode("\n", $input) as $line) {
            [$line, $counts] = explode(' ', $line);
            $counts = StringUtil::extractIntegers($counts);

            $sum += $this->combinations(
                implode('?', [$line, $line, $line, $line, $line]),
                [...$counts, ...$counts, ...$counts, ...$counts, ...$counts],
            );
        }

        return $sum;
    }

    private function combinations(string $line, array $counts): int
    {
        $line = trim($line, '.');
        $minLineLength = array_sum($counts) + count($counts) - 1;
        $key = $line . implode(',', $counts);

        if (isset($this->memory[$key])) {
            return $this->memory[$key];
        }

        $count = array_shift($counts);
        $isLast = [] === $counts;
        $combinations = 0;

        while (strlen($line) >= $minLineLength) {
            $requireEndOfPattern = $isLast ? '*$' : '';
            $match = preg_match('/^[?#]{'.$count.'}[?.]'.$requireEndOfPattern.'/', $line);

            if (1 === $match) {
                $combinations += $isLast ? 1 : $this->combinations(substr($line, $count + 1), $counts);
            }

            if (str_starts_with($line, '#')) {
                break;
            }

            $line = substr($line, 1);
        }

        $this->memory[$key] = $combinations;

        return $combinations;
    }
}
