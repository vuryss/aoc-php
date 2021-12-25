<?php

declare(strict_types=1);

namespace App\Event\Year2021;

use App\Event\DayInterface;

class Day25 implements DayInterface
{
    public function testPart1(): iterable
    {
        yield '58' => <<<'INPUT'
            v...>>.vv>
            .vv>>.vv..
            >>.>v>...v
            >>v>>.>.v.
            v>v.vv.v..
            >.>>..v...
            .vv..>.>v.
            v.v..>>v.v
            ....v..v.>
            INPUT;
    }

    public function testPart2(): iterable
    {
        return [];
    }

    public function solvePart1(string $input): string|int
    {
        $grid = array_map('str_split', explode("\n", $input));

        $steps = 0;

        do {
            $gridCopy = $grid;
            $move = false;
            $steps++;

            foreach ($grid as $y => $line) {
                foreach ($line as $x => $item) {
                    if ($item === '>') {
                        if (isset($line[$x + 1])) {
                            if ($line[$x + 1] === '.') {
                                $gridCopy[$y][$x+1] = '>';
                                $gridCopy[$y][$x] = '.';
                                $move = true;
                            }
                        } elseif (isset($line[0]) && $line[0] === '.') {
                            $gridCopy[$y][0] = '>';
                            $gridCopy[$y][$x] = '.';
                            $move = true;
                        }
                    }
                }
            }

            $grid = $gridCopy;

            foreach ($grid as $y => $line) {
                foreach ($line as $x => $item) {
                    if ($item === 'v') {
                        if (isset($grid[$y + 1][$x])) {
                            if ($grid[$y + 1][$x] === '.') {
                                $gridCopy[$y + 1][$x] = 'v';
                                $gridCopy[$y][$x] = '.';
                                $move = true;
                            }
                        } elseif (isset($grid[0][$x]) && $grid[0][$x] === '.') {
                            $gridCopy[0][$x] = 'v';
                            $gridCopy[$y][$x] = '.';
                            $move = true;
                        }
                    }
                }
            }

            $grid = $gridCopy;
        } while ($move);

        return $steps;
    }

    public function solvePart2(string $input): string|int
    {
        return '';
    }
}
