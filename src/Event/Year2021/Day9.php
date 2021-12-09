<?php

declare(strict_types=1);

namespace App\Event\Year2021;

use App\Event\DayInterface;
use App\Event\Year2021\Helpers\Point2;

class Day9 implements DayInterface
{
    public function testPart1(): iterable
    {
        yield '15' => <<<'INPUT'
            2199943210
            3987894921
            9856789892
            8767896789
            9899965678
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '1134' => <<<'INPUT'
            2199943210
            3987894921
            9856789892
            8767896789
            9899965678
            INPUT;
    }

    public function solvePart1(string $input): string
    {
        $lines = explode("\n", $input);
        $grid = array_map(static fn ($line): array => array_map('intval', str_split($line)), $lines);

        $lowPoints = $this->getLowPoints($grid);
        $sum = 0;

        foreach ($lowPoints as $lowPoint) {
            $sum += $grid[$lowPoint->y][$lowPoint->x] + 1;
        }

        return (string) $sum;
    }

    public function solvePart2(string $input): string
    {
        $lines = explode("\n", $input);
        $grid = array_map(static fn ($line): array => array_map('intval', str_split($line)), $lines);

        $lowPoints = $this->getLowPoints($grid);

        $basins = [];

        foreach ($lowPoints as $lowPointIndex => $lowPoint) {
            $queue = [$lowPoint];
            $visited = [];

            while (count($queue) > 0) {
                $point = array_shift($queue);

                if (isset($visited[$point->x][$point->y])) {
                    continue;
                }

                $visited[$point->x][$point->y] = true;

                if ($grid[$point->y][$point->x] !== 9) {
                    $basins[$lowPointIndex] = ($basins[$lowPointIndex] ?? 0) + 1;

                    if (isset($grid[$point->y + 1][$point->x])) {
                        $queue[] = new Point2(x: $point->x, y: $point->y + 1);
                    }

                    if (isset($grid[$point->y - 1][$point->x])) {
                        $queue[] = new Point2(x: $point->x, y: $point->y - 1);
                    }

                    if (isset($grid[$point->y][$point->x + 1])) {
                        $queue[] = new Point2(x: $point->x + 1, y: $point->y);
                    }

                    if (isset($grid[$point->y][$point->x - 1])) {
                        $queue[] = new Point2(x: $point->x - 1, y: $point->y);
                    }
                }
            }
        }

        rsort($basins);

        return (string) array_product(array_slice($basins, 0 ,3));
    }

    /**
     * @return Point2[]
     */
    private function getLowPoints(array $grid): array
    {
        $lowPoints = [];

        foreach ($grid as $lineIndex => $line) {
            foreach ($line as $charIndex => $depth) {
                if (
                       $depth < ($grid[$lineIndex]    [$charIndex + 1] ?? 10)
                    && $depth < ($grid[$lineIndex]    [$charIndex - 1] ?? 10)
                    && $depth < ($grid[$lineIndex - 1][$charIndex]     ?? 10)
                    && $depth < ($grid[$lineIndex + 1][$charIndex]     ?? 10)
                ) {
                    $lowPoints[] = new Point2($charIndex, $lineIndex);
                }
            }
        }

        return $lowPoints;
    }
}
