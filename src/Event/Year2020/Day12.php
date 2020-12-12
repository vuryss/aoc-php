<?php

declare(strict_types=1);

namespace App\Event\Year2020;

use App\Event\DayInterface;

class Day12 implements DayInterface
{
    public function testPart1(): iterable
    {
        yield '25' => <<<'INPUT'
            F10
            N3
            F7
            R90
            F11
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '286' => <<<'INPUT'
            F10
            N3
            F7
            R90
            F11
            INPUT;
    }

    public function solvePart1(string $input): string
    {
        $instructions = explode("\n", $input);
        $direction = 'E';
        $y = $x = 0;
        $leftTurn = ['E' => 'N', 'N' => 'W', 'W' => 'S', 'S' => 'E'];
        $rightTurn = ['E' => 'S', 'S' => 'W', 'W' => 'N', 'N' => 'E'];

        foreach ($instructions as $instruction) {
            $value = (int) substr($instruction, 1);
            switch ($instruction[0]) {
                case 'N':
                    $y += $value;
                    break;
                case 'S':
                    $y -= $value;
                    break;
                case 'E':
                    $x += $value;
                    break;
                case 'W':
                    $x -= $value;
                    break;
                case 'L':
                    $times = $value / 90;
                    for ($i = 0; $i < $times; $i++) {
                        $direction = $leftTurn[$direction];
                    }
                    break;
                case 'R':
                    $times = $value / 90;
                    for ($i = 0; $i < $times; $i++) {
                        $direction = $rightTurn[$direction];
                    }
                    break;
                case 'F':
                    switch ($direction) {
                        case 'N':
                            $y += $value;
                            break;
                        case 'S':
                            $y -= $value;
                            break;
                        case 'E':
                            $x += $value;
                            break;
                        case 'W':
                            $x -= $value;
                            break;
                    }
                    break;
            }
        }

        return (string) (abs($x) + abs($y));
    }

    public function solvePart2(string $input): string
    {
        $instructions = explode("\n", $input);
        $direction = 'NE';
        $y = $x = 0;
        $wx = 10;
        $wy = 1;
        $leftTurn = ['NE' => 'NW', 'NW' => 'SW', 'SW' => 'SE', 'SE' => 'NE'];
        $rightTurn = ['NE' => 'SE', 'SE' => 'SW', 'SW' => 'NW', 'NW' => 'NE'];

        foreach ($instructions as $instruction) {
            $value = (int) substr($instruction, 1);
            switch ($instruction[0]) {
                case 'N':
                    $wy += $value;
                    break;
                case 'S':
                    $wy -= $value;
                    break;
                case 'E':
                    $wx += $value;
                    break;
                case 'W':
                    $wx -= $value;
                    break;
                case 'L':
                    $times = $value / 90;
                    for ($i = 0; $i < $times; $i++) {
                        [$wx, $wy] = [-$wy, $wx];
                        $direction = $leftTurn[$direction];
                    }
                    break;
                case 'R':
                    $times = $value / 90;
                    for ($i = 0; $i < $times; $i++) {
                        [$wx, $wy] = [$wy, -$wx];
                        $direction = $rightTurn[$direction];
                    }
                    break;
                case 'F':
                    $x += $value * $wx;
                    $y += $value * $wy;
                    break;
            }
        }

        return (string) (abs($x) + abs($y));
    }
}
