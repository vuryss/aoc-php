<?php

declare(strict_types=1);

namespace App\Event\Year2023;

use App\Event\DayInterface;
use App\Util\Point2D;
use App\Util\RelativeDirection;
use App\Util\StringUtil;
use Ds\PriorityQueue;

class Day17 implements DayInterface
{
    public function testPart1(): iterable
    {
        yield '102' => <<<'INPUT'
            2413432311323
            3215453535623
            3255245654254
            3446585845452
            4546657867536
            1438598798454
            4457876987766
            3637877979653
            4654967986887
            4564679986453
            1224686865563
            2546548887735
            4322674655533
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '94' => <<<'INPUT'
            2413432311323
            3215453535623
            3255245654254
            3446585845452
            4546657867536
            1438598798454
            4457876987766
            3637877979653
            4654967986887
            4564679986453
            1224686865563
            2546548887735
            4322674655533
            INPUT;

        yield '71' => <<<'INPUT'
            111111111111
            999999999991
            999999999991
            999999999991
            999999999991
            INPUT;
    }

    public function solvePart1(string $input): string|int
    {
        return $this->solve($input, 1, 3);
    }

    public function solvePart2(string $input): string|int
    {
        return $this->solve($input, 4, 10);
    }

    public function solve(string $input, int $minStepsInStraightLine, int $maxStepsInStraightLine): int
    {
        $grid = StringUtil::inputToGridOfChars($input);
        $target = new Point2D(count($grid[0]) - 1, count($grid) - 1);
        $visited = $grid;

        foreach ($visited as $y => $line) {
            foreach ($line as $x => $value) {
                $visited[$y][$x] = [];
            }
        }

        $queue = new PriorityQueue();
        $queue->push(
            ['position' => new Point2D(0, 0), 'heatLoss' => 0, 'direction' => RelativeDirection::RIGHT, 'stepsInStraightLine' => 1],
            -1,
        );
        $queue->push(
            ['position' => new Point2D(0, 0), 'heatLoss' => 0, 'direction' => RelativeDirection::DOWN, 'stepsInStraightLine' => 1],
            -1,
        );

        while (!$queue->isEmpty()) {
            $crucible = $queue->pop();

            if ($crucible['position']->equals($target) && $crucible['stepsInStraightLine'] >= $minStepsInStraightLine) {
                return $crucible['heatLoss'];
            }

            if (isset($visited[$crucible['position']->y][$crucible['position']->x][$crucible['direction']->value][$crucible['stepsInStraightLine']])) {
                continue;
            }

            $visited[$crucible['position']->y][$crucible['position']->x][$crucible['direction']->value][$crucible['stepsInStraightLine']] = true;

            if ($crucible['stepsInStraightLine'] >= $minStepsInStraightLine) {
                $left = $crucible['position']->leftFromDirection($crucible['direction']);

                if (isset($grid[$left->y][$left->x])) {
                    $newCrucible = [
                        'position' => $left,
                        'heatLoss' => $crucible['heatLoss'] + (int)$grid[$left->y][$left->x],
                        'direction' => $crucible['direction']->turnLeft(),
                        'stepsInStraightLine' => 1,
                    ];

                    if (!isset($visited[$newCrucible['position']->y][$newCrucible['position']->x][$newCrucible['direction']->value][$newCrucible['stepsInStraightLine']])) {
                        $queue->push($newCrucible, -$newCrucible['heatLoss']);
                    }
                }

                $right = $crucible['position']->rightFromDirection($crucible['direction']);

                if (isset($grid[$right->y][$right->x])) {
                    $newCrucible = [
                        'position' => $right,
                        'heatLoss' => $crucible['heatLoss'] + (int)$grid[$right->y][$right->x],
                        'direction' => $crucible['direction']->turnRight(),
                        'stepsInStraightLine' => 1,
                    ];

                    if (!isset($visited[$newCrucible['position']->y][$newCrucible['position']->x][$newCrucible['direction']->value][$newCrucible['stepsInStraightLine']])) {
                        $queue->push($newCrucible, -$newCrucible['heatLoss']);
                    }
                }
            }

            if ($crucible['stepsInStraightLine'] < $maxStepsInStraightLine) {
                $forward = $crucible['position']->forwardFromDirection($crucible['direction']);

                if (isset($grid[$forward->y][$forward->x])) {
                    $newCrucible = [
                        'position' => $forward,
                        'heatLoss' => $crucible['heatLoss'] + (int) $grid[$forward->y][$forward->x],
                        'direction' => $crucible['direction'],
                        'stepsInStraightLine' => $crucible['stepsInStraightLine'] + 1,
                    ];

                    if (!isset($visited[$newCrucible['position']->y][$newCrucible['position']->x][$newCrucible['direction']->value][$newCrucible['stepsInStraightLine']])) {
                        $queue->push($newCrucible, -$newCrucible['heatLoss']);
                    }
                }
            }
        }

        return -1;
    }
}
