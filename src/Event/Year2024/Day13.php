<?php

declare(strict_types=1);

namespace App\Event\Year2024;

use App\Event\DayInterface;
use App\Util\StringUtil;

class Day13 implements DayInterface
{
    public function testPart1(): iterable
    {
        yield '480' => <<<'INPUT'
            Button A: X+94, Y+34
            Button B: X+22, Y+67
            Prize: X=8400, Y=5400
            
            Button A: X+26, Y+66
            Button B: X+67, Y+21
            Prize: X=12748, Y=12176
            
            Button A: X+17, Y+86
            Button B: X+84, Y+37
            Prize: X=7870, Y=6450
            
            Button A: X+69, Y+23
            Button B: X+27, Y+71
            Prize: X=18641, Y=10279
            INPUT;
    }

    public function testPart2(): iterable
    {
        return [];
    }

    public function solvePart1(string $input): string|int
    {
        $blocks = explode("\n\n", $input);
        $sum = 0;

        foreach ($blocks as $block) {
            $lines = explode("\n", $block);
            [$x1, $y1] = StringUtil::extractIntegers($lines[0]);
            [$x2, $y2] = StringUtil::extractIntegers($lines[1]);
            [$prizeX, $prizeY] = StringUtil::extractIntegers($lines[2]);

            for ($a = 0; $a < 100; $a++) {
                for ($b = 0; $b < 100; $b++) {
                    if ($x1 * $a + $x2 * $b === $prizeX && $y1 * $a + $y2 * $b === $prizeY) {
                        $sum += $a * 3 + $b;
                    }
                }
            }
        }

        return $sum;
    }

    public function solvePart2(string $input): string|int
    {
        $blocks = explode("\n\n", $input);
        $sum = 0;

        foreach ($blocks as $block) {
            $lines = explode("\n", $block);
            [$x1, $y1] = StringUtil::extractIntegers($lines[0]);
            [$x2, $y2] = StringUtil::extractIntegers($lines[1]);
            [$prizeX, $prizeY] = StringUtil::extractIntegers($lines[2]);
            $prizeX += 10000000000000;
            $prizeY += 10000000000000;
            $dividend = $prizeY * $x1 - $prizeX * $y1;
            $divisor = $y2 * $x1 - $x2 * $y1;

            if ($dividend % $divisor !== 0) {
                continue;
            }

            $b = $dividend / $divisor;

            if (($prizeX - $x2 * $b) % $x1 !== 0) {
                continue;
            }

            $a = ($prizeX - $x2 * $b) / $x1;
            $sum += $a * 3 + $b;
        }

        return (int) $sum;
    }
}
