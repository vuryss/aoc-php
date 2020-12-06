<?php

declare(strict_types=1);

namespace App\Event\Year2020;

use App\Event\DayInterface;

class Day6 implements DayInterface
{
    public function testPart1(): iterable
    {
        yield '11' => <<<'INPUT'
            abc
            
            a
            b
            c
            
            ab
            ac
            
            a
            a
            a
            a
            
            b
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '6' => <<<'INPUT'
            abc
            
            a
            b
            c
            
            ab
            ac
            
            a
            a
            a
            a
            
            b
            INPUT;

    }

    public function solvePart1(string $input): string
    {
        $input = explode("\n\n", $input);
        $count = 0;

        foreach ($input as $group) {
            $count += count(count_chars(strtr($group, ["\n" => '']), 1));
        }

        return (string) $count;
    }

    public function solvePart2(string $input): string
    {
        $input = explode("\n\n", $input);
        $count = 0;

        foreach ($input as $group) {
            $items = explode("\n", $group);
            $answers = array_map(fn ($item) => count_chars($item, 1), $items);
            $count += count(isset($answers[1]) ? array_intersect_key(...$answers) : $answers[0]);
        }

        return (string) $count;
    }
}
