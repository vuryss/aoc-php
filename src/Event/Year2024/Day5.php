<?php

declare(strict_types=1);

namespace App\Event\Year2024;

use App\Event\DayInterface;
use App\Util\StringUtil;

class Day5 implements DayInterface
{
    public function testPart1(): iterable
    {
        yield '143' => <<<'INPUT'
            47|53
            97|13
            97|61
            97|47
            75|29
            61|13
            75|53
            29|13
            97|29
            53|29
            61|53
            97|53
            61|29
            47|13
            75|47
            97|75
            47|61
            75|61
            47|29
            75|13
            53|13
            
            75,47,61,53,29
            97,61,53,29,13
            75,29,13
            75,97,47,61,53
            61,13,29
            97,13,75,29,47
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '123' => <<<'INPUT'
            47|53
            97|13
            97|61
            97|47
            75|29
            61|13
            75|53
            29|13
            97|29
            53|29
            61|53
            97|53
            61|29
            47|13
            75|47
            97|75
            47|61
            75|61
            47|29
            75|13
            53|13
            
            75,47,61,53,29
            97,61,53,29,13
            75,29,13
            75,97,47,61,53
            61,13,29
            97,13,75,29,47
            INPUT;
    }

    public function solvePart1(string $input): string|int
    {
        $parts = explode("\n\n", $input);
        $rules = array_map(StringUtil::extractIntegers(...), explode("\n", $parts[0]));
        $updates = array_map(StringUtil::extractIntegers(...), explode("\n", $parts[1]));
        $sum = 0;

        foreach ($updates as $line) {
            $pages = array_flip($line);

            foreach ($rules as $rule) {
                if (
                    array_key_exists($rule[0], $pages)
                    && array_key_exists($rule[1], $pages)
                    && $pages[$rule[0]] > $pages[$rule[1]]
                ) {
                    continue 2;
                }
            }

            $sum += $line[count($line) / 2];
        }

        return $sum;
    }

    public function solvePart2(string $input): string|int
    {
        $parts = explode("\n\n", $input);
        $rules = array_map(StringUtil::extractIntegers(...), explode("\n", $parts[0]));
        $updates = array_map(StringUtil::extractIntegers(...), explode("\n", $parts[1]));
        $sum = 0;
        $incorrectLines = [];

        foreach ($updates as $line) {
            $pages = array_flip($line);

            foreach ($rules as $rule) {
                if (
                    array_key_exists($rule[0], $pages)
                    && array_key_exists($rule[1], $pages)
                    && $pages[$rule[0]] > $pages[$rule[1]]
                ) {
                    $incorrectLines[] = $line;
                    break;
                }
            }
        }

        foreach ($incorrectLines as $line) {
            $pages = array_flip($line);
            $change = true;

            while ($change) {
                $change = false;

                foreach ($rules as $rule) {
                    if (
                        array_key_exists($rule[0], $pages)
                        && array_key_exists($rule[1], $pages)
                        && $pages[$rule[0]] > $pages[$rule[1]]
                    ) {
                        $temp = $pages[$rule[0]];
                        $pages[$rule[0]] = $pages[$rule[1]];
                        $pages[$rule[1]] = $temp;
                        $change = true;
                        break;
                    }
                }
            }

            $line = array_flip($pages);
            $sum += $line[count($line) / 2];
        }

        return $sum;
    }
}
