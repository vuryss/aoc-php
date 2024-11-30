<?php

declare(strict_types=1);

namespace App\Event\Year2017;

use App\Event\DayInterface;

class Day1 implements DayInterface
{
    public function testPart1(): iterable
    {
        yield '3' => <<<'INPUT'
            1122
            INPUT;

        yield '4' => <<<'INPUT'
            1111
            INPUT;

        yield '0' => <<<'INPUT'
            1234
            INPUT;

        yield '9' => <<<'INPUT'
            91212129
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '6' => <<<'INPUT'
            1212
            INPUT;

        yield '0' => <<<'INPUT'
            1221
            INPUT;

        yield '4' => <<<'INPUT'
            123425
            INPUT;

        yield '12' => <<<'INPUT'
            123123
            INPUT;

        yield '4' => <<<'INPUT'
            12131415
            INPUT;
    }

    public function solvePart1(string $input): string|int
    {
        $chars = str_split($input);
        $sum = 0;

        foreach ($chars as $index => $char) {
            if ($char === $chars[($index + 1) % count($chars)]) {
                $sum += (int)$char;
            }
        }

        return $sum;
    }

    public function solvePart2(string $input): string|int
    {
        $chars = str_split($input);
        $sum = 0;
        $add = count($chars) / 2;

        foreach ($chars as $index => $char) {
            if ($char === $chars[($index + $add) % count($chars)]) {
                $sum += (int)$char;
            }
        }

        return $sum;
    }
}
