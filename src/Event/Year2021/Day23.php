<?php

declare(strict_types=1);

namespace App\Event\Year2021;

use App\Event\DayInterface;
use App\Event\Year2021\Helpers\Burrow;
use App\Event\Year2021\Helpers\Point2;
use Ds\PriorityQueue;
use Ds\Queue;

class Day23 implements DayInterface
{
    private const DELTAS = [
        [-1, 0],
        [1, 0],
        [0, -1],
        [0, 1],
    ];

    private const COST = [
        'A' => 1,
        'B' => 10,
        'C' => 100,
        'D' => 1000,
    ];

    private Burrow $burrow;

    public function testPart1(): iterable
    {
        yield '12521' => <<<'INPUT'
            #############
            #...........#
            ###B#C#B#D###
              #A#D#C#A#
              #########
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '44169' => <<<'INPUT'
            #############
            #...........#
            ###B#C#B#D###
              #A#D#C#A#
              #########
            INPUT;
    }

    public function solvePart1(string $input): string|int
    {
        $maxLevel = 3;
        $lines = explode("\n", $input);

        return $this->solve($lines, $maxLevel);
    }

    public function solvePart2(string $input): string|int
    {
        $maxLevel = 5;
        $lines = explode("\n", $input);
        array_splice($lines, 3, 0, ['  #D#C#B#A#', '  #D#B#A#C#']);

        return $this->solve($lines, $maxLevel);
    }

    private function solve(array $lines, int $maxLevel): string|int
    {
        $this->burrow = new Burrow($maxLevel);

        $grid = [];
        $amps = [];
        $memory = [];

        foreach ($lines as $y => $line) {
            $chars = str_split($line);

            foreach ($chars as $x => $char) {
                if ($char === '.') {
                    $grid[$y][$x] = true;
                    continue;
                }

                if (in_array($char, ['A', 'B', 'C', 'D'])) {
                    $grid[$y][$x] = true;
                    $amps[$y][$x] = $char;
                }
            }
        }

        foreach ($amps as $key => $amp) {
            ksort($amps[$key]);
        }

        $queue = new PriorityQueue();
        $queue->push([0, $amps, []], 0);

        while (!$queue->isEmpty()) {
            /** @noinspection PhpUndefinedMethodInspection */
            [$cost, $ampPositions] = $queue->pop();

            ksort($ampPositions);
            $hash = serialize($ampPositions);

            if (isset($memory[$hash])) {
                continue;
            }

            $memory[$hash] = true;

            if ($this->isSolved($ampPositions)) {
                return $cost;
            }

            foreach ($ampPositions as $y => $xAmpPositions) {
                foreach ($xAmpPositions as $x => $ampType) {
                    $point = new Point2($x, $y);

                    // Amp is in place - do not move
                    if ($this->isInPlace($point, $ampType, $ampPositions, $maxLevel)) {
                        continue;
                    }

                    $isLocked = $point->y === 1;
                    $tempQueue = new Queue();
                    $tempQueue->push([$ampType, $point, $cost, $ampPositions]);
                    $visited = [];

                    while (!$tempQueue->isEmpty()) {
                        [$ampType2, $position2, $cost2, $ampPositions2] = $tempQueue->pop();
                        $visited[$position2->x][$position2->y] = true;

                        foreach (self::DELTAS as [$dx, $dy]) {
                            $newPoint = new Point2($position2->x + $dx, $position2->y + $dy);

                            if (
                                !isset($grid[$newPoint->y][$newPoint->x])
                                || isset($visited[$newPoint->x][$newPoint->y])
                                || isset($ampPositions[$newPoint->y][$newPoint->x])
                            ) {
                                continue;
                            }

                            $isRoom = $newPoint->y > 1;

                            if ($isRoom && $newPoint->x !== $point->x) {
                                if (!$this->burrow->isAmphipodTypeRoom($ampType2, $newPoint)) {
                                    continue;
                                }

                                foreach ($this->burrow->getAmphipodTypeRooms($ampType2) as $roomPosition) {
                                    if (($ampPositions2[$roomPosition->y][$roomPosition->x] ?? $ampType2) !== $ampType2) {
                                        continue 2;
                                    }
                                }
                            }

                            $newCost = $cost2 + self::COST[$ampType];
                            $newAmpPositions = $ampPositions2;
                            unset($newAmpPositions[$position2->y][$position2->x]);
                            $newAmpPositions[$newPoint->y][$newPoint->x] = $ampType2;

                            $tempQueue->push([$ampType2, $newPoint, $newCost, $newAmpPositions]);

                            // Check if the spot is allowed
                            if (!$this->burrow->isForbidden($newPoint)) {
                                if (!$isLocked || $isRoom) {
                                    if ($point->x !== $newPoint->x) {
                                        $hasEmptyBelow = false;

                                        for ($nextY = $newPoint->y + 1; $nextY <= $maxLevel; $nextY++) {
                                            if (!isset($ampPositions[$nextY][$newPoint->x])) {
                                                $hasEmptyBelow = true;
                                                break;
                                            }
                                        }

                                        if (!$isRoom || !$hasEmptyBelow) {
                                            ksort($newAmpPositions[$newPoint->y]);
                                            $queue->push([$newCost, $newAmpPositions], -$newCost);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return 'error';
    }

    private function isSolved(array $ampPositions): bool
    {
        foreach ($ampPositions as $y => $xPosition) {
            foreach ($xPosition as $x => $ampType) {
                if (!$this->burrow->isAmphipodTypeRoom($ampType, new Point2($x, $y))) {
                    return false;
                }
            }
        }

        return true;
    }

    private function isInPlace(Point2 $point, string $ampType, array $ampPositions, int $maxLevel): bool
    {
        if (!$this->burrow->isAmphipodTypeRoom($ampType, $point)) {
            return false;
        }

        for ($y = $point->y + 1; $y <= $maxLevel; $y++) {
            if (!isset($ampPositions[$y][$point->x]) || $ampPositions[$y][$point->x] !== $ampType) {
                return false;
            }
        }

        return true;
    }
}
