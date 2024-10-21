<?php

declare(strict_types=1);

namespace App\Event\Year2023;

use App\Event\DayInterface;
use App\Util\Point2D;

class Day3 implements DayInterface
{
    public function testPart1(): iterable
    {
        yield '4361' => <<<'INPUT'
            467..114..
            ...*......
            ..35..633.
            ......#...
            617*......
            .....+.58.
            ..592.....
            ......755.
            ...$.*....
            .664.598..
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '467835' => <<<'INPUT'
            467..114..
            ...*......
            ..35..633.
            ......#...
            617*......
            .....+.58.
            ..592.....
            ......755.
            ...$.*....
            .664.598..
            INPUT;
    }

    public function solvePart1(string $input): string|int
    {
        $lines = explode("\n", $input);
        $grid = [];
        $sum = 0;

        foreach ($lines as $line) {
            $grid[] = str_split($line);
        }

        for ($lineIndex = 0; $lineIndex < count($grid); $lineIndex++) {
            $number = '';
            $surrounding = [];

            for ($columnIndex = 0; $columnIndex < count($grid[$lineIndex]); $columnIndex++) {
                $point = new Point2D($columnIndex, $lineIndex);

                if (ctype_digit($grid[$lineIndex][$columnIndex])) {
                    $number .= $grid[$lineIndex][$columnIndex];
                    $surrounding = [...$surrounding, ...$point->surrounding()];
                } else {
                    if ('' !== $number && $this->isAnyOfTheSurroundingPointsSymbol($grid, $surrounding)) {
                        $sum += (int) $number;
                    }

                    $number = '';
                    $surrounding = [];
                }
            }

            if ('' !== $number && $this->isAnyOfTheSurroundingPointsSymbol($grid, $surrounding)) {
                $sum += (int) $number;
            }
        }

        return $sum;
    }

    public function solvePart2(string $input): string|int
    {
        $lines = explode("\n", $input);
        $grid = [];
        $sum = 0;

        foreach ($lines as $line) {
            $grid[] = str_split($line);
        }

        for ($lineIndex = 0; $lineIndex < count($grid); $lineIndex++) {
            for ($columnIndex = 0; $columnIndex < count($grid[$lineIndex]); $columnIndex++) {
                if ('*' !== $grid[$lineIndex][$columnIndex]) {
                    continue;
                }

                $point = new Point2D($columnIndex, $lineIndex);
                $countNumbers = 0;
                /** @var Point2D[] $numberPoints */
                $numberPoints = [];
                $uniqueNumberPoint = [];

                foreach ($point->surrounding() as $surroundingPoint) {
                    $value = $grid[$surroundingPoint->y][$surroundingPoint->x] ?? '.';

                    if (!ctype_digit($value)) {
                        continue;
                    }

                    $numberPoints[] = $surroundingPoint;
                    $numberAlreadyAccountedFor = false;

                    foreach ($numberPoints as $numberPoint) {
                        if ($numberPoint->isHorizontallyAdjacent($surroundingPoint)) {
                            $numberAlreadyAccountedFor = true;
                            break;
                        }
                    }

                    if (!$numberAlreadyAccountedFor) {
                        $countNumbers++;
                        $uniqueNumberPoint[] = $surroundingPoint;
                    }
                }

                if (2 !== $countNumbers) {
                    continue;
                }

                $result = 1;

                foreach ($uniqueNumberPoint as $point) {
                    $number = $grid[$point->y][$point->x];
                    $leftClone = $point->clone();
                    $rightClone = $point->clone();

                    while (isset($grid[$leftClone->y][--$leftClone->x]) && ctype_digit($grid[$leftClone->y][$leftClone->x])) {
                        $number = $grid[$leftClone->y][$leftClone->x] . $number;
                    }

                    while (isset($grid[$rightClone->y][++$rightClone->x]) && ctype_digit($grid[$rightClone->y][$rightClone->x])) {
                        $number .= $grid[$rightClone->y][$rightClone->x];
                    }

                    $result *= (int) $number;
                }

                $sum += $result;
            }
        }

        return $sum;
    }

    private function isAnyOfTheSurroundingPointsSymbol(array $grid, array $surroundingPoints): bool
    {
        foreach ($surroundingPoints as $point) {
            $value = $grid[$point->y][$point->x] ?? '.';

            if (!ctype_digit($value) && '.' !== $value) {
                return true;
            }
        }

        return false;
    }
}
