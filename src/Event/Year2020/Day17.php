<?php

declare(strict_types=1);

namespace App\Event\Year2020;

use App\Event\DayInterface;

class Day17 implements DayInterface
{
    public function testPart1(): iterable
    {
        yield '112' => <<<'INPUT'
            .#.
            ..#
            ###
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '848' => <<<'INPUT'
            .#.
            ..#
            ###
            INPUT;
    }

    public function solvePart1(string $input): string
    {
        $input = array_map(fn (string $line) => str_split($line), explode("\n", $input));
        $space = [];
        $xMin = $yMin = $zMin = -1;
        $xMax = count($input[0]) + 1;
        $yMax = count($input) + 1;
        $zMax = 1;


        foreach ($input as $y => $line) {
            foreach ($line as $x => $item) {
                $space[$x][$y][0] = $item === '#';
            }
        }

        for ($cycle = 1; $cycle <= 6; $cycle++) {
            $newSpace = $space;


            // Iterate whole space
            for ($x = $xMin; $x <= $xMax; $x++) {
                for ($y = $yMin; $y <= $yMax; $y++) {
                    for ($z = $zMin; $z <= $zMax; $z++) {


                        // Count neighbours
                        $activeNeighbours = 0;

                        for ($dx = $x - 1; $dx <= $x + 1; $dx++) {
                            for ($dy = $y - 1; $dy <= $y + 1; $dy++) {
                                for ($dz = $z - 1; $dz <= $z + 1; $dz++) {
                                    if ($dx === $x && $dy === $y && $dz === $z) continue;
                                    if ($space[$dx][$dy][$dz] ?? false) $activeNeighbours ++;
                                }
                            }
                        }

                        $isActive = $space[$x][$y][$z] ?? false;

                        if ($isActive) {
                            if ($activeNeighbours !== 2 && $activeNeighbours !== 3) {
                                $newSpace[$x][$y][$z] = false;
                            }
                        } elseif ($activeNeighbours === 3) {
                            $newSpace[$x][$y][$z] = true;
                            if ($x - 1 < $xMin) $xMin = $x - 1;
                            if ($x + 1 > $xMax) $xMax = $x + 1;
                            if ($y - 1 < $yMin) $yMin = $y - 1;
                            if ($y + 1 > $yMax) $yMax = $y + 1;
                            if ($z - 1 < $zMin) $zMin = $z - 1;
                            if ($z + 1 > $zMax) $zMax = $z + 1;
                        }
                    }
                }
            }

            $space = $newSpace;
        }

        // Iterate whole space
        $active = 0;

        for ($x = $xMin; $x <= $xMax; $x++) {
            for ($y = $yMin; $y <= $yMax; $y++) {
                for ($z = $zMin; $z <= $zMax; $z++) {
                    if ($space[$x][$y][$z] ?? false) $active++;
                }
            }
        }

        return (string) $active;
    }

    public function solvePart2(string $input): string
    {
        $input = array_map(fn (string $line) => str_split($line), explode("\n", $input));
        $space = [];
        $xMin = $yMin = $zMin = $wMin = -1;
        $xMax = count($input[0]) + 1;
        $yMax = count($input) + 1;
        $zMax = $wMax = 1;


        foreach ($input as $y => $line) {
            foreach ($line as $x => $item) {
                $space[$x][$y][0][0] = $item === '#';
            }
        }

        for ($cycle = 1; $cycle <= 6; $cycle++) {
            $newSpace = $space;


            // Iterate whole space
            for ($x = $xMin; $x <= $xMax; $x++) {
                for ($y = $yMin; $y <= $yMax; $y++) {
                    for ($z = $zMin; $z <= $zMax; $z++) {
                        for ($w = $wMin; $w <= $wMax; $w++) {


                            // Count neighbours
                            $activeNeighbours = 0;

                            for ($dx = $x - 1; $dx <= $x + 1; $dx++) {
                                for ($dy = $y - 1; $dy <= $y + 1; $dy++) {
                                    for ($dz = $z - 1; $dz <= $z + 1; $dz++) {
                                        for ($dw = $w - 1; $dw <= $w + 1; $dw++) {
                                            if ($dx === $x && $dy === $y && $dz === $z && $dw === $w) continue;
                                            if ($space[$dx][$dy][$dz][$dw] ?? false) $activeNeighbours ++;
                                        }
                                    }
                                }
                            }

                            $isActive = $space[$x][$y][$z][$w] ?? false;

                            if ($isActive) {
                                if ($activeNeighbours !== 2 && $activeNeighbours !== 3) {
                                    $newSpace[$x][$y][$z][$w] = false;
                                }
                            } elseif ($activeNeighbours === 3) {
                                $newSpace[$x][$y][$z][$w] = true;
                                if ($x - 1 < $xMin) $xMin = $x - 1;
                                if ($x + 1 > $xMax) $xMax = $x + 1;
                                if ($y - 1 < $yMin) $yMin = $y - 1;
                                if ($y + 1 > $yMax) $yMax = $y + 1;
                                if ($z - 1 < $zMin) $zMin = $z - 1;
                                if ($z + 1 > $zMax) $zMax = $z + 1;
                                if ($w - 1 < $wMin) $wMin = $w - 1;
                                if ($w + 1 > $wMax) $wMax = $w + 1;
                            }


                        }
                    }
                }
            }

            $space = $newSpace;
        }

        // Iterate whole space
        $active = 0;

        for ($x = $xMin; $x <= $xMax; $x++) {
            for ($y = $yMin; $y <= $yMax; $y++) {
                for ($z = $zMin; $z <= $zMax; $z++) {
                    for ($w = $wMin; $w <= $wMax; $w++) {
                        if ($space[$x][$y][$z][$w] ?? false) $active++;
                    }
                }
            }
        }

        return (string) $active;
    }
}
