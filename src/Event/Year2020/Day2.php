<?php

declare(strict_types=1);

namespace App\Event\Year2020;

use App\Event\DayInterface;

class Day2 implements DayInterface
{
    public function testPart1(): iterable
    {
        yield '2' => <<<'INPUT'
            1-3 a: abcde
            1-3 b: cdefg
            2-9 c: ccccccccc
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '1' => <<<'INPUT'
            1-3 a: abcde
            1-3 b: cdefg
            2-9 c: ccccccccc
            INPUT;
    }

    public function solvePart1(string $input): string
    {
        $list = explode("\n", $input);
        $count = 0;

        foreach ($list as $item) {
            preg_match('/(\d+)-(\d+)\s(\w):\s(\w+)$/', $item, $matches);
            [, $from, $to, $char, $password] = $matches;
            $times = count_chars($password, 1)[ord($char)] ?? 0;

            if ($times >= $from && $times <= $to) {
                $count++;
            }
        }

        return (string) $count;
    }

    public function solvePart2(string $input): string
    {
        $list = explode("\n", $input);
        $count = 0;

        foreach ($list as $item) {
            preg_match('/(\d+)-(\d+)\s(\w):\s(\w+)$/', $item, $matches);
            [, $pos1, $pos2, $char, $password] = $matches;

            $firstChar = $password[(int) $pos1 - 1] ?? '';
            $secondChar = $password[(int) $pos2 - 1] ?? '';

            if ($firstChar === $char && $secondChar !== $char || $firstChar !== $char && $secondChar === $char) {
                $count++;
            }
        }

        return (string) $count;
    }
}
