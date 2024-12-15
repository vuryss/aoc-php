<?php

declare(strict_types=1);

namespace App\Event\Year2024;

use App\Event\DayInterface;
use App\Util\ArrowDirection;
use App\Util\Point2D;
use App\Util\StringUtil;
use Ds\Queue;

class Day15 implements DayInterface
{
    public function testPart1(): iterable
    {
        yield '2028' => <<<'INPUT'
            ########
            #..O.O.#
            ##@.O..#
            #...O..#
            #.#.O..#
            #...O..#
            #......#
            ########
            
            <^^>>>vv<v>>v<<
            INPUT;

        yield '10092' => <<<'INPUT'
            ##########
            #..O..O.O#
            #......O.#
            #.OO..O.O#
            #..O@..O.#
            #O#..O...#
            #O..O..O.#
            #.OO.O.OO#
            #....O...#
            ##########
            
            <vv>^<v^>v>^vv^v>v<>v^v<v<^vv<<<^><<><>>v<vvv<>^v^>^<<<><<v<<<v^vv^v>^
            vvv<<^>^v^^><<>>><>^<<><^vv^^<>vvv<>><^^v>^>vv<>v<<<<v<^v>^<^^>>>^<v<v
            ><>vv>v^v^<>><>>>><^^>vv>v<^^^>>v^v^<^^>v^^>v^<^v>v<>>v^v^<v>v^^<^^vv<
            <<v<^>>^^^^>>>v^<>vvv^><v<<<>^^^vv^<vvv>^>v<^^^^v<>^>vvvv><>>v^<<^^^^^
            ^><^><>>><>^^<<^^v>>><^<v>^<vv>>v>>>^v><>^v><<<<v>>v<v<v>vvv>^<><<>^><
            ^>><>^v<><^vvv<^^<><v<<<<<><^v<<<><<<^^<v<^^^><^>>^<v^><<<^>>^v<v^v<v^
            >^>>^v>vv>^<<^v<>><<><<v<<v><>v<^vv<<<>^^v^>^^>>><<^v>>v^v><^^>>^<>vv^
            <><^^>^^^<><vvvvv^v<v<<>^v<v>v<<^><<><<><<<^^<<<^<<>><<><^^^>^^<>^>v<>
            ^^>vv<^v^v<vv>^<><v<^v>^^^>>>^^vvv^>vvv<>>>^<^>>>>>^<<^v>^vvv<>^<><<v>
            v^^>>><<^^<>>^v^<v^vv<>v^<<>^<^v^v><^<<<><<^<v><v<>vv>>v><v^<vv<>v^<<^
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '9021' => <<<'INPUT'
            ##########
            #..O..O.O#
            #......O.#
            #.OO..O.O#
            #..O@..O.#
            #O#..O...#
            #O..O..O.#
            #.OO.O.OO#
            #....O...#
            ##########
            
            <vv>^<v^>v>^vv^v>v<>v^v<v<^vv<<<^><<><>>v<vvv<>^v^>^<<<><<v<<<v^vv^v>^
            vvv<<^>^v^^><<>>><>^<<><^vv^^<>vvv<>><^^v>^>vv<>v<<<<v<^v>^<^^>>>^<v<v
            ><>vv>v^v^<>><>>>><^^>vv>v<^^^>>v^v^<^^>v^^>v^<^v>v<>>v^v^<v>v^^<^^vv<
            <<v<^>>^^^^>>>v^<>vvv^><v<<<>^^^vv^<vvv>^>v<^^^^v<>^>vvvv><>>v^<<^^^^^
            ^><^><>>><>^^<<^^v>>><^<v>^<vv>>v>>>^v><>^v><<<<v>>v<v<v>vvv>^<><<>^><
            ^>><>^v<><^vvv<^^<><v<<<<<><^v<<<><<<^^<v<^^^><^>>^<v^><<<^>>^v<v^v<v^
            >^>>^v>vv>^<<^v<>><<><<v<<v><>v<^vv<<<>^^v^>^^>>><<^v>>v^v><^^>>^<>vv^
            <><^^>^^^<><vvvvv^v<v<<>^v<v>v<<^><<><<><<<^^<<<^<<>><<><^^^>^^<>^>v<>
            ^^>vv<^v^v<vv>^<><v<^v>^^^>>>^^vvv^>vvv<>>>^<^>>>>>^<<^v>^vvv<>^<><<v>
            v^^>>><<^^<>>^v^<v^vv<>v^<<>^<^v^v><^<<<><<^<v><v<>vv>>v><v^<vv<>v^<<^
            INPUT;
    }

    public function solvePart1(string $input): string|int
    {
        [$mapInput, $moves] = explode("\n\n", $input);
        $grid = StringUtil::inputToGridOfChars($mapInput);
        $moves = array_map(ArrowDirection::from(...), str_split(str_replace("\n", '', $moves)));

        foreach ($grid as $y => $xLine) {
            foreach ($xLine as $x => $char) {
                if ($char === '@') {
                    $robot = new Point2D($x, $y);
                }
            }
        }

        foreach ($moves as $move) {
            $next = $move->fromPoint($robot);
            $robotNext = $next;
            $nodesToMove = [['pos' => $robot, 'char' => '@']];
            $newGrid = $grid;

            while ($grid[$next->y][$next->x] === 'O') {
                $nodesToMove[] = ['pos' => $next, 'char' => 'O'];
                $next = $move->fromPoint($next);
            }

            if ($grid[$next->y][$next->x] !== '.') {
                continue;
            }

            foreach ($nodesToMove as $boxToMove) {
                $newGrid[$boxToMove['pos']->y][$boxToMove['pos']->x] = '.';
            }

            foreach ($nodesToMove as $boxToMove) {
                $next = $move->fromPoint($boxToMove['pos']);
                $newGrid[$next->y][$next->x] = $boxToMove['char'];
            }

            $robot = $robotNext;
            $grid = $newGrid;
        }

        return $this->sumPoints($grid);
    }

    public function solvePart2(string $input): string|int
    {
        [$mapInput, $moves] = explode("\n\n", $input);
        $moves = array_map(ArrowDirection::from(...), str_split(str_replace("\n", '', $moves)));
        $grid = [];

        foreach (explode("\n", $mapInput) as $y => $line) {
            $grid[$y] = [];

            foreach (str_split($line) as $char) {
                array_push(
                    $grid[$y],
                    ...match ($char) {'#' => ['#', '#'], 'O' => ['[', ']'], '.' => ['.', '.'], '@' => ['@', '.']}
                );
            }
        }

        foreach ($grid as $y => $xLine) {
            foreach ($xLine as $x => $char) {
                if ($char === '@') {
                    $robot = new Point2D($x, $y);
                }
            }
        }

        foreach ($moves as $move) {
            $next = $move->fromPoint($robot);
            $robotNext = $next;
            $nodesToMove = [['pos' => $robot, 'char' => '@']];
            $newGrid = $grid;

            if ($grid[$next->y][$next->x] === '#') {
                continue;
            }

            if ($grid[$next->y][$next->x] === '[' || $grid[$next->y][$next->x] === ']') {
                $visited = [];
                $queue = new Queue();
                $queue->push($next);

                if ($grid[$next->y][$next->x] === '[') {
                    $queue->push($next->east());
                }

                if ($grid[$next->y][$next->x] === ']') {
                    $queue->push($next->west());
                }

                while (!$queue->isEmpty()) {
                    $box = $queue->pop();
                    $nodesToMove[] = ['pos' => $box, 'char' => $grid[$box->y][$box->x]];

                    if (isset($visited[$box->y][$box->x])) {
                        continue;
                    }

                    $visited[$box->y][$box->x] = true;
                    $next = $move->fromPoint($box);
                    $nextChar = $grid[$next->y][$next->x];

                    if ($nextChar === '[') {
                        $queue->push($next);
                        $queue->push($next->east());
                    } elseif ($nextChar === ']') {
                        $queue->push($next);
                        $queue->push($next->west());
                    } elseif ($nextChar !== '.') {
                        continue 2;
                    }
                }
            }

            foreach ($nodesToMove as $boxToMove) {
                $pos = $boxToMove['pos'];
                $newGrid[$pos->y][$pos->x] = '.';
            }

            foreach ($nodesToMove as $boxToMove) {
                $next = $move->fromPoint($boxToMove['pos']);
                $newGrid[$next->y][$next->x] = $boxToMove['char'];
            }

            $robot = $robotNext;
            $grid = $newGrid;
        }

        return $this->sumPoints($grid);
    }

    public function sumPoints(array $grid): int
    {
        $sum = 0;

        foreach ($grid as $y => $xLine) {
            foreach ($xLine as $x => $char) {
                if ($char === 'O' || $char === '[') {
                    $sum += 100 * $y + $x;
                }
            }
        }

        return $sum;
    }
}
