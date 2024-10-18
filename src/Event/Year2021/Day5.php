<?php

declare(strict_types=1);

namespace App\Event\Year2021;

use App\Event\DayInterface;

class Day5 implements DayInterface
{
    public function testPart1(): iterable
    {
        yield '5' => <<<'INPUT'
            0,9 -> 5,9
            8,0 -> 0,8
            9,4 -> 3,4
            2,2 -> 2,1
            7,0 -> 7,4
            6,4 -> 2,0
            0,9 -> 2,9
            3,4 -> 1,4
            0,0 -> 8,8
            5,5 -> 8,2
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '12' => <<<'INPUT'
            0,9 -> 5,9
            8,0 -> 0,8
            9,4 -> 3,4
            2,2 -> 2,1
            7,0 -> 7,4
            6,4 -> 2,0
            0,9 -> 2,9
            3,4 -> 1,4
            0,0 -> 8,8
            5,5 -> 8,2
            INPUT;
    }

    public function solvePart1(string $input): string
    {
        return (string) $this->getNumberOfDangerousPoints($input);
    }

    public function solvePart2(string $input): string
    {
        return (string) $this->getNumberOfDangerousPoints($input, true);
    }

    private function getNumberOfDangerousPoints(string $input, $includeDiagonals = false): int
    {
        $linesDef = explode("\n", $input);
        $grid = [];

        foreach ($linesDef as $lineDef) {
            [$x1, $y1, $x2, $y2] = sscanf($lineDef, '%d,%d -> %d,%d');

            if (!$includeDiagonals && $x1 !== $x2 && $y1 !== $y2) {
                continue;
            }

            $x = $x1;
            $y = $y1;
            $vents = max(abs($x1 - $x2), abs($y1 - $y2));

            for ($i = 0; $i <= $vents; $i++) {
                $grid[$y][$x] = ($grid[$y][$x] ?? 0) + 1;
                $x += $x2 <=> $x1;
                $y += $y2 <=> $y1;
            }
        }

        $count = 0;

        foreach ($grid as $line) {
            foreach ($line as $vents) {
                $count += ($vents > 1) ? 1 : 0;
            }
        }

        return $count;
    }
}
