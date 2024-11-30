<?php

declare(strict_types=1);

namespace App\Event\Year2015;

use App\Event\DayInterface;

class Day1 implements DayInterface
{
    public function testPart1(): iterable
    {
        yield '0' => <<<'INPUT'
            (())
            INPUT;

        yield '0' => <<<'INPUT'
            ()()
            INPUT;

        yield '3' => <<<'INPUT'
            (((
            INPUT;

        yield '3' => <<<'INPUT'
            (()(()(
            INPUT;

        yield '3' => <<<'INPUT'
            ))(((((
            INPUT;

        yield '-1' => <<<'INPUT'
            ())
            INPUT;

        yield '-1' => <<<'INPUT'
            ))(
            INPUT;

        yield '-3' => <<<'INPUT'
            )))
            INPUT;

        yield '-3' => <<<'INPUT'
            )())())
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '1' => <<<'INPUT'
            )
            INPUT;

        yield '5' => <<<'INPUT'
            ()())
            INPUT;
    }

    public function solvePart1(string $input): string|int
    {
        return substr_count($input, '(') - substr_count($input, ')');
    }

    public function solvePart2(string $input): string|int
    {
        $floor = 0;

        foreach (str_split($input) as $index => $char) {
            $floor += $char === '(' ? 1 : -1;

            if ($floor === -1) {
                return $index + 1;
            }
        }

        throw new \Exception('Basement not reached');
    }
}
