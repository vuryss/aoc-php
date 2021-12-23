<?php

declare(strict_types=1);

namespace App\Event\Year2021;

use App\Event\DayInterface;
use App\Event\Year2021\Helpers\Amphipod;
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

        $availablePositions = [];
        $amphipodPositions = [];

        foreach ($lines as $y => $line) {
            foreach (str_split($line) as $x => $char) {
                if ($char === '.') {
                    $availablePositions[$y][$x] = true;
                } elseif (isset(Amphipod::TYPES[$char])) {
                    $availablePositions[$y][$x] = true;
                    $amphipodPositions[$y][$x] = ['type' => $char];
                }
            }
        }

        foreach ($amphipodPositions as $y => $line) {
            foreach ($line as $x => ['type' => $type]) {
                $amphipodPositions[$y][$x] = [
                    'type' => $type,
                    'inPlace' => $this->isInPlace(new Point2($x, $y), $type, $amphipodPositions, $maxLevel)
                ];
            }
        }

        $storedAmphipodStates = [];

        $amphipodPositionsByCost = new PriorityQueue();
        $amphipodPositionsByCost->push([0, $amphipodPositions], 0);

        while (!$amphipodPositionsByCost->isEmpty()) {
            /** @noinspection PhpUndefinedMethodInspection */
            [$cost, $ampPositions] = $amphipodPositionsByCost->pop();

            ksort($ampPositions);
            $hash = serialize($ampPositions);

            if (isset($storedAmphipodStates[$hash])) {
                continue;
            }

            $storedAmphipodStates[$hash] = true;

            if ($this->isSolved($ampPositions)) {
                return $cost;
            }

            foreach ($ampPositions as $y => $line) {
                foreach ($line as $x => ['type' => $ampType, 'inPlace' => $inPlace]) {
                    // Amp is in place - do not move
                    if ($inPlace) {
                        continue;
                    }

                    $point = new Point2($x, $y);
                    $isLocked = $point->y === 1;
                    $amphipodMovement = new Queue([[$ampType, $point, $cost, $ampPositions]]);
                    $visited = [];

                    while (!$amphipodMovement->isEmpty()) {
                        [$ampType2, $position2, $cost2, $ampPositions2] = $amphipodMovement->pop();
                        $visited[$position2->x][$position2->y] = true;

                        foreach (self::DELTAS as [$dx, $dy]) {
                            $newPoint = new Point2($position2->x + $dx, $position2->y + $dy);

                            if (
                                !isset($availablePositions[$newPoint->y][$newPoint->x])
                                || isset($visited[$newPoint->x][$newPoint->y])
                                || isset($ampPositions[$newPoint->y][$newPoint->x])
                            ) {
                                continue;
                            }

                            $isNewPointRoom = $newPoint->y > 1;

                            if ($isNewPointRoom && $newPoint->x !== $point->x) {
                                if (!$this->burrow->isAmphipodTypeRoom($ampType2, $newPoint)) {
                                    continue;
                                }

                                foreach ($this->burrow->getAmphipodTypeRooms($ampType2) as $roomPosition) {
                                    if (($ampPositions2[$roomPosition->y][$roomPosition->x]['type'] ?? $ampType2) !== $ampType2) {
                                        continue 2;
                                    }
                                }
                            }

                            $newCost = $cost2 + Amphipod::MOVE_COST[$ampType];
                            $newAmpPositions = $ampPositions2;
                            unset($newAmpPositions[$position2->y][$position2->x]);
                            $newAmpPositions[$newPoint->y][$newPoint->x] = ['type' => $ampType2, 'inPlace' => $isNewPointRoom];

                            $amphipodMovement->push([$ampType2, $newPoint, $newCost, $newAmpPositions]);

                            // Check if the new position is valid placement of amphipod after a move
                            if ($point->x !== $newPoint->x && !$this->burrow->isForbidden($newPoint)) {
                                if (!$isLocked || $isNewPointRoom) {
                                    if (!$isNewPointRoom || !$this->hasEmptySpaceBelow($ampPositions, $newPoint, $maxLevel)) {
                                        ksort($newAmpPositions[$newPoint->y]);
                                        $amphipodPositionsByCost->push([$newCost, $newAmpPositions], -$newCost);
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

    private function hasEmptySpaceBelow(array $ampPositions, Point2 $point, int $maxLevel): bool
    {
        for ($y = $point->y + 1; $y <= $maxLevel; $y++) {
            if (!isset($ampPositions[$y][$point->x])) {
                return true;
            }
        }

        return false;
    }

    private function isSolved(array $ampPositions): bool
    {
        foreach ($ampPositions as $y => $xPosition) {
            foreach ($xPosition as $x => ['type' => $ampType]) {
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
            if (!isset($ampPositions[$y][$point->x]) || $ampPositions[$y][$point->x]['type'] !== $ampType) {
                return false;
            }
        }

        return true;
    }
}
