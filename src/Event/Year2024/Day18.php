<?php

declare(strict_types=1);

namespace App\Event\Year2024;

use App\Event\DayInterface;
use App\Util\Point2D;
use Ds\Queue;

class Day18 implements DayInterface
{
    public function testPart1(): iterable
    {
        yield '22' => <<<'INPUT'
            5,4
            4,2
            4,5
            3,0
            2,1
            6,3
            2,4
            1,5
            0,6
            3,3
            2,6
            5,1
            1,2
            5,5
            2,5
            6,5
            1,4
            0,4
            6,4
            1,1
            6,1
            1,0
            0,5
            1,6
            2,0
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '6,1' => <<<'INPUT'
            5,4
            4,2
            4,5
            3,0
            2,1
            6,3
            2,4
            1,5
            0,6
            3,3
            2,6
            5,1
            1,2
            5,5
            2,5
            6,5
            1,4
            0,4
            6,4
            1,1
            6,1
            1,0
            0,5
            1,6
            2,0
            INPUT;
    }

    public function solvePart1(string $input): string|int
    {
        $lines = explode("\n", $input);
        $size = count($lines) < 1024 ? 6 : 70;
        $maxBytes = count($lines) < 1024 ? 12 : 1024;
        $grid = [];

        for ($i = 0; $i < $maxBytes; $i++) {
            [$x, $y] = array_map('intval', explode(',', $lines[$i]));
            $grid[$y][$x] = true;
        }

        $start = new Point2D(0, 0);
        $queue = new Queue();
        $queue->push([$start, 0]);
        $visited = [];

        while (!$queue->isEmpty()) {
            [$point, $steps] = $queue->pop();

            if (isset($visited[$point->y][$point->x])) {
                continue;
            }

            $visited[$point->y][$point->x] = true;

            if ($point->x === $size && $point->y === $size) {
                return $steps;
            }

            foreach ($point->adjacent() as $adjacent) {
                if (
                    $adjacent->x >= 0 && $adjacent->y >= 0
                    && $adjacent->x <= $size && $adjacent->y <= $size
                    && !isset($visited[$adjacent->y][$adjacent->x])
                    && !isset($grid[$adjacent->y][$adjacent->x])
                ) {
                    $queue->push([$adjacent, $steps + 1]);
                }
            }
        }

        throw new \RuntimeException('No path found');
    }

    public function solvePart2(string $input): string|int
    {
        $lines = explode("\n", $input);
        $size = count($lines) < 1024 ? 6 : 70;
        $maxBytes = count($lines) < 1024 ? 12 : 1024;
        $grid = [];

        for ($i = 0; $i < $maxBytes; $i++) {
            [$x, $y] = array_map('intval', explode(',', $lines[$i]));
            $grid[$y][$x] = true;
        }

        for ($i = $maxBytes; $i < count($lines); $i++) {
            [$x, $y] = array_map('intval', explode(',', $lines[$i]));
            $grid[$y][$x] = true;
            $start = new Point2D(0, 0);
            $queue = new Queue();
            $queue->push($start);
            $visited = [];

            while (!$queue->isEmpty()) {
                $point = $queue->pop();

                if (isset($visited[$point->y][$point->x])) {
                    continue;
                }

                $visited[$point->y][$point->x] = true;

                if ($point->x === $size && $point->y === $size) {
                    continue 2;
                }

                foreach ($point->adjacent() as $adjacent) {
                    if (
                        $adjacent->x >= 0 && $adjacent->y >= 0
                        && $adjacent->x <= $size && $adjacent->y <= $size
                        && !isset($visited[$adjacent->y][$adjacent->x])
                        && !isset($grid[$adjacent->y][$adjacent->x])
                    ) {
                        $queue->push($adjacent);
                    }
                }
            }

            return "$x,$y";
        }
    }
}
