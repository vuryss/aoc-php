<?php

declare(strict_types=1);

namespace App\Event\Year2021;

use App\Event\DayInterface;

class Day13 implements DayInterface
{
    public function testPart1(): iterable
    {
        yield '17' => <<<'INPUT'
            6,10
            0,14
            9,10
            0,3
            10,4
            4,11
            6,0
            6,12
            4,1
            0,13
            10,12
            3,4
            3,0
            8,4
            1,10
            2,14
            8,10
            9,0
            
            fold along y=7
            fold along x=5
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '' => <<<'INPUT'
            6,10
            0,14
            9,10
            0,3
            10,4
            4,11
            6,0
            6,12
            4,1
            0,13
            10,12
            3,4
            3,0
            8,4
            1,10
            2,14
            8,10
            9,0
            
            fold along y=7
            fold along x=5
            INPUT;
    }

    public function solvePart1(string $input): string
    {
        $grid = $this->foldPaper($input);

        $count = array_reduce($grid, fn ($carry, $value) => $carry + count($value), 0);

        return (string) $count;
    }

    public function solvePart2(string $input): string
    {
        $grid = $this->foldPaper($input, false);

        for ($y = 0; $y <= 5; $y++) {
            for ($x = 0; $x <= 38; $x++) {
                echo $grid[$y][$x] ?? ' ';
            }
            echo PHP_EOL;
        }

        return '';
    }

    private function foldPaper(string $input, bool $onlyFirst = true): array
    {
        [$coordinates, $folds] = explode("\n\n", $input);
        $grid = [];

        foreach (explode("\n", $coordinates) as $coords) {
            [$x, $y] = sscanf($coords, '%d,%d');
            $grid[$y][$x] = '#';
        }

        foreach (explode("\n", $folds) as $fold) {
            [, , $fold, $amount] = sscanf($fold, '%s %s %[^=]=%d');
            $grid = $fold === 'y' ? $this->foldY($grid, $amount) : $this->foldX($grid, $amount);

            if ($onlyFirst) {
                break;
            }
        }

        return $grid;
    }

    private function foldY(array $grid, int $foldValue): array
    {
        $newGrid = [];

        foreach ($grid as $y => $line) {
            $y = $y > $foldValue ? $foldValue - ($y - $foldValue) : $y;
            foreach ($line as $x => $dot) {
                $newGrid[$y][$x] = $dot;
            }
        }

        return $newGrid;
    }

    private function foldX(array $grid, int $foldValue): array
    {
        $newGrid = [];

        foreach ($grid as $y => $line) {
            foreach ($line as $x => $dot) {
                if ($x > $foldValue) {
                    $x = $foldValue - ($x - $foldValue);
                }
                $newGrid[$y][$x] = $dot;
            }
        }

        return $newGrid;
    }
}
