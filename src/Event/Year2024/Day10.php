<?php

declare(strict_types=1);

namespace App\Event\Year2024;

use App\Event\DayInterface;
use App\Util\Point2D;
use App\Util\StringUtil;
use Ds\Queue;

class Day10 implements DayInterface
{
    public function testPart1(): iterable
    {
        yield '2' => <<<'INPUT'
            ...0...
            ...1...
            ...2...
            6543456
            7.....7
            8.....8
            9.....9
            INPUT;

        yield '4' => <<<'INPUT'
            ..90..9
            ...1.98
            ...2..7
            6543456
            765.987
            876....
            987....
            INPUT;

        yield '3' => <<<'INPUT'
            10..9..
            2...8..
            3...7..
            4567654
            ...8..3
            ...9..2
            .....01
            INPUT;

        yield '36' => <<<'INPUT'
            89010123
            78121874
            87430965
            96549874
            45678903
            32019012
            01329801
            10456732
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '3' => <<<'INPUT'
            .....0.
            ..4321.
            ..5..2.
            ..6543.
            ..7..4.
            ..8765.
            ..9....
            INPUT;

        yield '13' => <<<'INPUT'
            ..90..9
            ...1.98
            ...2..7
            6543456
            765.987
            876....
            987....
            INPUT;

        yield '227' => <<<'INPUT'
            012345
            123456
            234567
            345678
            4.6789
            56789.
            INPUT;

        yield '81' => <<<'INPUT'
            89010123
            78121874
            87430965
            96549874
            45678903
            32019012
            01329801
            10456732
            INPUT;
    }

    public function solvePart1(string $input): string|int
    {
        $grid = StringUtil::inputToGridOfChars($input);
        $startPoints = [];
        $maxX = count($grid[0]) - 1;
        $maxY = count($grid) - 1;
        $sum = 0;

        foreach ($grid as $y => $row) {
            foreach ($row as $x => $char) {
                if ($char === '0') {
                    $startPoints[] = new Point2D($x, $y);
                }
            }
        }

        foreach ($startPoints as $startPoint) {
            $queue = new Queue();
            $queue->push([$startPoint, 0]);
            $visited = [];
            $score = 0;

            while (!$queue->isEmpty()) {
                /** @var Point2D $point */
                [$point, $el] = $queue->pop();

                if (isset($visited[$point->x][$point->y])) {
                    continue;
                }

                $visited[$point->x][$point->y] = true;

                if ($el === 9) {
                    $score++;
                    continue;
                }

                foreach ($point->adjacent() as $adjacent) {
                    if (
                        $adjacent->x >= 0 && $adjacent->y >= 0
                        && $adjacent->x <= $maxX && $adjacent->y <= $maxY
                        && (int) $grid[$adjacent->y][$adjacent->x] === $el + 1
                        && !isset($visited[$adjacent->x][$adjacent->y])
                    ) {
                        $queue->push([new Point2D($adjacent->x, $adjacent->y), $el + 1]);
                    }
                }
            }

            $sum += $score;
        }

        return $sum;
    }

    public function solvePart2(string $input): string|int
    {
        $grid = StringUtil::inputToGridOfChars($input);
        $startPoints = [];
        $maxX = count($grid[0]) - 1;
        $maxY = count($grid) - 1;
        $sum = 0;

        foreach ($grid as $y => $row) {
            foreach ($row as $x => $char) {
                if ($char === '0') {
                    $startPoints[] = new Point2D($x, $y);
                }
            }
        }

        foreach ($startPoints as $startPoint) {
            $queue = new Queue();
            $queue->push([$startPoint, 0, []]);
            $score = 0;

            while (!$queue->isEmpty()) {
                /** @var Point2D $point */
                [$point, $el, $visited] = $queue->pop();

                if (isset($visited[$point->x][$point->y])) {
                    continue;
                }

                $visited[$point->x][$point->y] = true;

                if ($el === 9) {
                    $score++;
                    continue;
                }

                foreach ($point->adjacent() as $adjacent) {
                    if (
                        $adjacent->x >= 0 && $adjacent->y >= 0
                        && $adjacent->x <= $maxX && $adjacent->y <= $maxY
                        && (int) $grid[$adjacent->y][$adjacent->x] === $el + 1
                        && !isset($visited[$adjacent->x][$adjacent->y])
                    ) {
                        $queue->push([new Point2D($adjacent->x, $adjacent->y), $el + 1, $visited]);
                    }
                }
            }

            $sum += $score;
        }

        return $sum;
    }
}
