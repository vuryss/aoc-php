<?php

declare(strict_types=1);

namespace App\Event\Year2024;

use App\Event\DayInterface;
use App\Util\Point2D;
use App\Util\StringUtil;

class Day8 implements DayInterface
{
    public function testPart1(): iterable
    {
        yield '14' => <<<'INPUT'
            ............
            ........0...
            .....0......
            .......0....
            ....0.......
            ......A.....
            ............
            ............
            ........A...
            .........A..
            ............
            ............
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '34' => <<<'INPUT'
            ............
            ........0...
            .....0......
            .......0....
            ....0.......
            ......A.....
            ............
            ............
            ........A...
            .........A..
            ............
            ............
            INPUT;
    }

    public function solvePart1(string $input): string|int
    {
        $grid = StringUtil::inputToGridOfChars($input);
        $chars = [];
        $antiNodes = [];
        $maxY = count($grid) - 1;
        $maxX = count($grid[0]) - 1;

        foreach ($grid as $y => $line) {
            foreach ($line as $x => $char) {
                if ($char !== '.') {
                    $chars[$char][] = new Point2D($x, $y);
                }
            }
        }

        foreach ($chars as $points) {
            for ($i = 0; $i < count($points) - 1; $i++) {
                for ($j = $i + 1; $j < count($points); $j++) {
                    [$xDiff, $yDiff] = [$points[$j]->x - $points[$i]->x, $points[$j]->y - $points[$i]->y];
                    [$ax, $ay] = [$points[$i]->x - $xDiff, $points[$i]->y - $yDiff];

                    if ($ax >= 0 && $ax <= $maxX && $ay >= 0 && $ay <= $maxY) {
                        $antiNodes[$ax][$ay] = true;
                    }

                    [$ax, $ay] = [$points[$j]->x + $xDiff, $points[$j]->y + $yDiff];

                    if ($ax >= 0 && $ax <= $maxX && $ay >= 0 && $ay <= $maxY) {
                        $antiNodes[$ax][$ay] = true;
                    }
                }
            }
        }

        return count($antiNodes, COUNT_RECURSIVE) - count($antiNodes);
    }

    public function solvePart2(string $input): string|int
    {
        $grid = StringUtil::inputToGridOfChars($input);
        $chars = [];
        $antiNodes = [];
        $maxY = count($grid) - 1;
        $maxX = count($grid[0]) - 1;

        foreach ($grid as $y => $line) {
            foreach ($line as $x => $char) {
                if ($char !== '.') {
                    $chars[$char][] = new Point2D($x, $y);
                }
            }
        }

        foreach ($chars as $points) {
            for ($i = 0; $i < count($points) - 1; $i++) {
                for ($j = $i + 1; $j < count($points); $j++) {
                    [$xDiff, $yDiff] = [$points[$j]->x - $points[$i]->x, $points[$j]->y - $points[$i]->y];
                    $antiNodes[$points[$i]->x][$points[$i]->y] = true;
                    $antiNodes[$points[$j]->x][$points[$j]->y] = true;
                    [$ax, $ay] = [$points[$i]->x - $xDiff, $points[$i]->y - $yDiff];

                    while ($ax >= 0 && $ax <= $maxX && $ay >= 0 && $ay <= $maxY) {
                        $antiNodes[$ax][$ay] = true;
                        $ax -= $xDiff;
                        $ay -= $yDiff;
                    }

                    [$ax, $ay] = [$points[$j]->x + $xDiff, $points[$j]->y + $yDiff];

                    while ($ax >= 0 && $ax <= $maxX && $ay >= 0 && $ay <= $maxY) {
                        $antiNodes[$ax][$ay] = true;
                        $ax += $xDiff;
                        $ay += $yDiff;
                    }
                }
            }
        }

        return count($antiNodes, COUNT_RECURSIVE) - count($antiNodes);
    }
}
