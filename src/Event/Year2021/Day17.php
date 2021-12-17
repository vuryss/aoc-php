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

        $possibleX = $this->determinePossibleXVelocities($targetMinX, $targetMaxX);
        $maxHeight = 0;

        foreach (array_keys($possibleX) as $initX) {
            for ($initY = 0; $initY <= abs($targetMinY); $initY++) {
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

        $possibleX = $this->determinePossibleXVelocities($targetMinX, $targetMaxX);

        $allVelocities = [];

        foreach (array_keys($possibleX) as $initX) {
            for ($initY = $targetMinY; $initY <= abs($targetMinY); $initY++) {
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

    private function determinePossibleXVelocities(int $xTargetStart, int $xTargetEnd): array
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

        return $possibleX;
    }
}
