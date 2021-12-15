<?php

declare(strict_types=1);

namespace App\Event\Year2021;

use App\Event\DayInterface;
use App\Event\Year2021\Helpers\Point2;
use Ds\PriorityQueue;

class Day15 implements DayInterface
{
    private const DELTAS = [
        [-1, 0],
        [1, 0],
        [0, -1],
        [0, 1],
    ];

    public function testPart1(): iterable
    {
        yield '40' => <<<'INPUT'
            1163751742
            1381373672
            2136511328
            3694931569
            7463417111
            1319128137
            1359912421
            3125421639
            1293138521
            2311944581
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '315' => <<<'INPUT'
            1163751742
            1381373672
            2136511328
            3694931569
            7463417111
            1319128137
            1359912421
            3125421639
            1293138521
            2311944581
            INPUT;
    }

    public function solvePart1(string $input): string
    {
        $grid = array_map(fn ($line) => array_map('intval', str_split($line)), explode("\n", $input));

        $endX = count($grid[0]) - 1;
        $endY = count($grid) - 1;

        $queue = new PriorityQueue();
        $queue->push([new Point2(0, 0), 0], 0);
        $visited = [];

        while (!$queue->isEmpty()) {
            /** @var Point2 $current */
            [$current, $risk] = $queue->pop();

            if (isset($visited[$current->x][$current->y])) {
                continue;
            }

            $visited[$current->x][$current->y] = true;

            if ($current->x === $endX && $current->y === $endY) {
                return (string) $risk;
            }

            foreach (self::DELTAS as [$dx, $dy]) {
                $x = $current->x + $dx;
                $y = $current->y + $dy;

                if (isset($grid[$y][$x])) {
                    $newRisk = $risk + $grid[$y][$x];
                    $queue->push([new Point2($x, $y), $newRisk], 0 - $newRisk);
                }
            }
        }

        return '';
    }

    public function solvePart2(string $input): string
    {
        $grid = array_map(fn ($line) => array_map('intval', str_split($line)), explode("\n", $input));

        $sizeX = count($grid[0]);
        $sizeY = count($grid);

        $endX = count($grid[0]) * 5 - 1;
        $endY = count($grid) * 5 - 1;

        $queue = new PriorityQueue();
        $queue->push([new Point2(0, 0), 0], 0);
        $visited = [];

        while (!$queue->isEmpty()) {
            /** @var Point2 $current */
            [$current, $risk] = $queue->pop();

            if (isset($visited[$current->x][$current->y])) {
                continue;
            }

            $visited[$current->x][$current->y] = true;

            if ($current->x === $endX && $current->y === $endY) {
                return (string) $risk;
            }

            foreach (self::DELTAS as [$dx, $dy]) {
                $x = $current->x + $dx;
                $y = $current->y + $dy;

                if ($x <= $endX && $y <= $endY && $x >= 0 && $y >= 0) {
                    $originalRisk = $grid[$y % $sizeY][$x % $sizeX];
                    $overflow = (int) ($x / $sizeX) + (int) ($y / $sizeY);

                    $newRisk = $originalRisk + $overflow;
                    $newRisk -= ceil($newRisk / 9 - 1) * 9;
                    $newRisk += $risk;

                    $queue->push([new Point2($x, $y), $newRisk], 0 - $newRisk);
                }
            }
        }

        return '';
    }
}
