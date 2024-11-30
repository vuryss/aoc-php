<?php

declare(strict_types=1);

namespace App\Event\Year2016;

use App\Event\DayInterface;
use App\Util\CompassDirection;

class Day1 implements DayInterface
{
    public function testPart1(): iterable
    {
        yield '5' => <<<'INPUT'
            R2, L3
            INPUT;

        yield '2' => <<<'INPUT'
            R2, R2, R2
            INPUT;

        yield '12' => <<<'INPUT'
            R5, L5, R5, R3
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '4' => <<<'INPUT'
            R8, R4, R4, R8
            INPUT;
    }

    public function solvePart1(string $input): string|int
    {
        $parts = explode(', ', $input);
        $dir = CompassDirection::NORTH;
        $x = 0;
        $y = 0;

        foreach ($parts as $part) {
            $dir = $part[0] === 'R' ? $dir->turnRight() : $dir->turnLeft();
            $steps = (int)substr($part, 1);
            $x += CompassDirection::EAST === $dir ? $steps : (CompassDirection::WEST === $dir ? -$steps : 0);
            $y += CompassDirection::NORTH === $dir ? $steps : (CompassDirection::SOUTH === $dir ? -$steps : 0);
        }

        return abs($x) + abs($y);
    }

    public function solvePart2(string $input): string|int
    {
        $parts = explode(', ', $input);
        $dir = CompassDirection::NORTH;
        $x = 0;
        $y = 0;
        $visited = [0 => [0 => true]];

        foreach ($parts as $part) {
            $dir = $part[0] === 'R' ? $dir->turnRight() : $dir->turnLeft();
            $steps = (int)substr($part, 1);

            for ($i = 0; $i < $steps; $i++) {
                $x += CompassDirection::EAST === $dir ? 1 : (CompassDirection::WEST === $dir ? -1 : 0);
                $y += CompassDirection::NORTH === $dir ? 1 : (CompassDirection::SOUTH === $dir ? -1 : 0);

                if (isset($visited[$x][$y])) {
                    return abs($x) + abs($y);
                }

                $visited[$x][$y] = true;
            }
        }

        throw new \Exception('No location visited twice');
    }
}
