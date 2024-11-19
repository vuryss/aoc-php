<?php

declare(strict_types=1);

namespace App\Event\Year2023;

use App\Event\DayInterface;
use App\Util\Algorithms;
use App\Util\Point2D;

class Day18 implements DayInterface
{
    public function testPart1(): iterable
    {
        yield '62' => <<<'INPUT'
            R 6 (#70c710)
            D 5 (#0dc571)
            L 2 (#5713f0)
            D 2 (#d2c081)
            R 2 (#59c680)
            D 2 (#411b91)
            L 5 (#8ceee2)
            U 2 (#caa173)
            L 1 (#1b58a2)
            U 2 (#caa171)
            R 2 (#7807d2)
            U 3 (#a77fa3)
            L 2 (#015232)
            U 2 (#7a21e3)
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '952408144115' => <<<'INPUT'
            R 6 (#70c710)
            D 5 (#0dc571)
            L 2 (#5713f0)
            D 2 (#d2c081)
            R 2 (#59c680)
            D 2 (#411b91)
            L 5 (#8ceee2)
            U 2 (#caa173)
            L 1 (#1b58a2)
            U 2 (#caa171)
            R 2 (#7807d2)
            U 3 (#a77fa3)
            L 2 (#015232)
            U 2 (#7a21e3)
            INPUT;
    }

    public function solvePart1(string $input): string|int
    {
        $lines = explode("\n", $input);
        $from = new Point2D(0, 0);
        $points = [$from];
        $perimeter = 0;

        foreach ($lines as $line) {
            [$direction, $distance] = sscanf($line, '%s %d');
            $perimeter += $distance;

            $to = match($direction) {
                'U' => $from->north($distance),
                'D' => $from->south($distance),
                'L' => $from->west($distance),
                'R' => $from->east($distance),
            };

            $points[] = $to;
            $from = $to;
        }

        $shoelaceArea = Algorithms::shoelaceArea($points);

        return $shoelaceArea + (int) ($perimeter / 2) + 1;
    }

    public function solvePart2(string $input): string|int
    {
        $lines = explode("\n", $input);
        $from = new Point2D(0, 0);
        $points = [$from];
        $perimeter = 0;

        foreach ($lines as $line) {
            [,, $hex] = sscanf($line, '%s %d (#%6s)');
            $direction = match(substr($hex, 5, 1)) {
                '0' => 'R',
                '1' => 'D',
                '2' => 'L',
                '3' => 'U',
            };
            $distance = (int) base_convert(substr($hex, 0, 5), 16, 10);
            $perimeter += $distance;

            $to = match($direction) {
                'U' => $from->north($distance),
                'D' => $from->south($distance),
                'L' => $from->west($distance),
                'R' => $from->east($distance),
            };

            $points[] = $to;
            $from = $to;
        }

        $shoelaceArea = Algorithms::shoelaceArea($points);

        return $shoelaceArea + (int) ($perimeter / 2) + 1;
    }
}
