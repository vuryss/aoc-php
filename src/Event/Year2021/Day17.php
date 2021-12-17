<?php

declare(strict_types=1);

namespace App\Event\Year2021;

use App\Event\DayInterface;
use App\Event\Year2021\Helpers\Point2;

class Day17 implements DayInterface
{
    public function testPart1(): iterable
    {
        yield '45' => <<<'INPUT'
            target area: x=20..30, y=-10..-5
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '112' => <<<'INPUT'
            target area: x=20..30, y=-10..-5
            INPUT;
    }

    public function solvePart1(string $input): string
    {
        [$targetMinX, $targetMaxX, $targetMinY, $targetMaxY] = sscanf($input, 'target area: x=%d..%d, y=%d..%d');

        $possibleX = $this->determinePossibleHorizontalVelocities($targetMinX, $targetMaxX);
        $possibleY = $this->determinePossibleVerticalVelocities($targetMaxY, $targetMinY);
        $maxHeight = 0;

        foreach ($possibleX as $initX) {
            foreach ($possibleY as $initY) {
                $xVelocity = $initX;
                $yVelocity = $initY;
                $tempMaxHeight = 0;
                $position = new Point2(0, 0);

                while (true) {
                    $position->x += $xVelocity > 0 ? $xVelocity-- : 0;
                    $position->y += $yVelocity--;

                    $tempMaxHeight = max($position->y, $tempMaxHeight);

                    if (
                        $position->x >= $targetMinX
                        && $position->x <= $targetMaxX
                        && $position->y <= $targetMaxY
                        && $position->y >= $targetMinY
                    ) {
                        if ($tempMaxHeight > $maxHeight) {
                            $maxHeight = $tempMaxHeight;
                        }
                        break;
                    }

                    if ($position->x > $targetMaxX || $position->y < $targetMinY) {
                        break;
                    }
                }
            }
        }

        return (string) $maxHeight;
    }

    public function solvePart2(string $input): string
    {
        [$targetMinX, $targetMaxX, $targetMinY, $targetMaxY] = sscanf($input, 'target area: x=%d..%d, y=%d..%d');

        $possibleX = $this->determinePossibleHorizontalVelocities($targetMinX, $targetMaxX);
        $possibleY = $this->determinePossibleVerticalVelocities($targetMaxY, $targetMinY);
        $allVelocities = [];

        foreach ($possibleX as $initX) {
            foreach ($possibleY as $initY) {
                $xVelocity = $initX;
                $yVelocity = $initY;
                $position = new Point2(0, 0);

                while (true) {
                    $position->x += $xVelocity > 0 ? $xVelocity-- : 0;
                    $position->y += $yVelocity--;

                    if (
                        $position->x >= $targetMinX
                        && $position->x <= $targetMaxX
                        && $position->y <= $targetMaxY
                        && $position->y >= $targetMinY
                    ) {
                        $allVelocities[$initX . 'x' . $initY] = true;
                        break;
                    }

                    if ($position->x > $targetMaxX || $position->y < $targetMinY) {
                        break;
                    }
                }
            }
        }

        return (string) count($allVelocities);
    }

    private function determinePossibleHorizontalVelocities(int $xTargetStart, int $xTargetEnd): array
    {
        $possibleX = [];

        for ($x = 1; $x <= $xTargetEnd; $x++) {
            $xVelocity = $x;
            $xPosition = 0;

            while (true) {
                $xPosition += $xVelocity--;

                if ($xPosition >= $xTargetStart && $xPosition <= $xTargetEnd) {
                    $possibleX[$x] = true;
                }

                if ($xVelocity === 0 || $xPosition > $xTargetEnd) {
                    break;
                }
            }
        }

        return array_keys($possibleX);
    }

    private function determinePossibleVerticalVelocities(int $yTargetStart, int $yTargetEnd): array
    {
        $possibleY = [];

        for ($y = $yTargetEnd; $y <= abs($yTargetEnd); $y++) {
            $yVelocity = $y;
            $yPosition = 0;

            while (true) {
                $yPosition += $yVelocity--;

                if ($yPosition <= $yTargetStart && $yPosition >= $yTargetEnd) {
                    $possibleY[$y] = true;
                }

                if ($yPosition < $yTargetEnd) {
                    break;
                }
            }
        }

        return array_keys($possibleY);
    }
}
