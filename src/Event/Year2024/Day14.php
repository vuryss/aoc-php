<?php

declare(strict_types=1);

namespace App\Event\Year2024;

use App\Event\DayInterface;
use App\Util\StringUtil;

class Day14 implements DayInterface
{
    public function testPart1(): iterable
    {
        yield '12' => <<<'INPUT'
            p=0,4 v=3,-3
            p=6,3 v=-1,-3
            p=10,3 v=-1,2
            p=2,0 v=2,-1
            p=0,0 v=1,3
            p=3,0 v=-2,-2
            p=7,6 v=-1,-3
            p=3,0 v=-1,-2
            p=9,3 v=2,3
            p=7,3 v=-1,2
            p=2,4 v=2,-3
            p=9,5 v=-3,-3
            INPUT;
    }

    public function testPart2(): iterable
    {
        return [];
    }

    public function solvePart1(string $input): string|int
    {
        $robots = [];

        foreach (explode("\n", $input) as $line) {
            $ints = StringUtil::extractIntegers($line);
            $robots[] = ['x' => $ints[0], 'y' => $ints[1], 'vx' => $ints[2], 'vy' => $ints[3]];
        }

        $gridWidth = count($robots) > 20 ? 101 : 11;
        $gridHeight = count($robots) > 20 ? 103 : 7;

        for ($j = 0; $j < 100; $j++) {
            foreach ($robots as $i => $robot) {
                $robots[$i]['x'] += $robot['vx'];
                $robots[$i]['x'] = ($robots[$i]['x'] % $gridWidth + $gridWidth) % $gridWidth;
                $robots[$i]['y'] += $robot['vy'];
                $robots[$i]['y'] = ($robots[$i]['y'] % $gridHeight + $gridHeight) % $gridHeight;
            }
        }

        $quadrants = [0, 0, 0, 0];
        $midX = (int) ($gridWidth / 2);
        $midY = (int) ($gridHeight / 2);

        foreach ($robots as $robot) {
            if ($robot['x'] !== $midX && $robot['y'] !== $midY) {
                $quadrant = ($robot['x'] > $midX ? 1: 0) + ($robot['y'] > $midY ? 2: 0);
                $quadrants[$quadrant]++;
            }
        }

        return array_product($quadrants);
    }

    public function solvePart2(string $input): string|int
    {
        $robots = [];
        $gridWidth = 101;
        $gridHeight = 103;
        $seconds = 0;

        foreach (explode("\n", $input) as $line) {
            $ints = StringUtil::extractIntegers($line);
            $robots[] = ['x' => $ints[0], 'y' => $ints[1], 'vx' => $ints[2], 'vy' => $ints[3]];
        }

        while (++$seconds) {
            $set = [];

            foreach ($robots as $i => $robot) {
                $robots[$i]['x'] += $robot['vx'];
                $robots[$i]['x'] = ($robots[$i]['x'] % $gridWidth + $gridWidth) % $gridWidth;
                $robots[$i]['y'] += $robot['vy'];
                $robots[$i]['y'] = ($robots[$i]['y'] % $gridHeight + $gridHeight) % $gridHeight;

                $set["{$robots[$i]['x']},{$robots[$i]['y']}"] = true;
            }

            if (count($set) === count($robots)) {
                return $seconds;
            }
        }

        return -1;
    }
}
