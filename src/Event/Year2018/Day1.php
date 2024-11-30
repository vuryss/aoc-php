<?php

declare(strict_types=1);

namespace App\Event\Year2018;

use App\Event\DayInterface;
use App\Util\StringUtil;

class Day1 implements DayInterface
{
    public function testPart1(): iterable
    {
        yield '3' => <<<'INPUT'
            +1, -2, +3, +1
            INPUT;

        yield '3' => <<<'INPUT'
            +1, +1, +1
            INPUT;

        yield '0' => <<<'INPUT'
            +1, +1, -2
            INPUT;

        yield '-6' => <<<'INPUT'
            -1, -2, -3
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '2' => <<<'INPUT'
            +1, -2, +3, +1
            INPUT;

        yield '0' => <<<'INPUT'
            +1, -1
            INPUT;

        yield '10' => <<<'INPUT'
            +3, +3, +4, -2, -4
            INPUT;

        yield '5' => <<<'INPUT'
            -6, +3, +8, +5, -6
            INPUT;

        yield '14' => <<<'INPUT'
            +7, +7, -2, -7, -4
            INPUT;
    }

    public function solvePart1(string $input): string|int
    {
        return (int) array_sum(StringUtil::extractIntegers($input));
    }

    public function solvePart2(string $input): string|int
    {
        $numbers = StringUtil::extractIntegers($input);
        $reached = [0 => true];
        $frequency = 0;

        while (true) {
            foreach ($numbers as $number) {
                $frequency += $number;

                if (isset($reached[$frequency])) {
                    return $frequency;
                }

                $reached[$frequency] = true;
            }
        }

        throw new \Exception('No frequency reached twice');
    }
}
