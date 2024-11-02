<?php

declare(strict_types=1);

namespace App\Event\Year2023;

use App\Event\DayInterface;
use App\Event\Year2023\Day10\Player;
use App\Event\Year2023\Day10\Tile;
use App\Util\CompassDirection;
use App\Util\Grid;
use App\Util\Point2D;
use Ds\Queue;

class Day10 implements DayInterface
{
    public function testPart1(): iterable
    {
        yield '4' => <<<'INPUT'
            -L|F7
            7S-7|
            L|7||
            -L-J|
            L|-JF
            INPUT;

        yield '8' => <<<'INPUT'
            ..F7.
            .FJ|.
            SJ.L7
            |F--J
            LJ...
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '4' => <<<'INPUT'
            ...........
            .S-------7.
            .|F-----7|.
            .||.....||.
            .||.....||.
            .|L-7.F-J|.
            .|..|.|..|.
            .L--J.L--J.
            ...........
            INPUT;

        yield '8' => <<<'INPUT'
            .F----7F7F7F7F-7....
            .|F--7||||||||FJ....
            .||.FJ||||||||L7....
            FJL7L7LJLJ||LJ.L-7..
            L--J.L7...LJS7F-7L7.
            ....F-J..F7FJ|L7L7L7
            ....L7.F7||L7|.L7L7|
            .....|FJLJ|FJ|F7|.LJ
            ....FJL-7.||.||||...
            ....L---J.LJ.LJLJ...
            INPUT;

        yield '10' => <<<'INPUT'
            FF7FSF7F7F7F7F7F---7
            L|LJ||||||||||||F--J
            FL-7LJLJ||||||LJL-77
            F--JF--7||LJLJ7F7FJ-
            L---JF-JLJ.||-FJLJJ7
            |F|F-JF---7F7-L7L|7|
            |FFJF7L7F-JF7|JL---7
            7-L-JL7||F7|L7F-7F7|
            L.L7LFJ|||||FJL7||LJ
            L7JLJL-JLJLJL--JLJ.L
            INPUT;
    }

    public function solvePart1(string $input): string|int
    {
        $grid = $this->constructPipeGrid($input);

        return (int) ($grid->totalNodes() / 2);
    }

    public function solvePart2(string $input): string|int
    {
        $lines = explode("\n", $input);
        $pipe = $this->constructPipeGrid($input);
        $count = 0;

        foreach ($lines as $y => $line) {
            $cleanLine = '';

            foreach (str_split($line) as $x => $char) {
                $cleanLine .= $pipe->get(new Point2D($x, $y))?->value ?? '.';
            }

            $cleanLine = trim($cleanLine, Tile::GROUND->value);
            $cleanLine = preg_replace('/(L-*7)|(F-*J)/', Tile::VERTICAL->value, $cleanLine);
            $pipes = 0;

            foreach (str_split($cleanLine) as $char) {
                if (Tile::VERTICAL->value === $char) {
                    $pipes++;
                } elseif (Tile::GROUND->value === $char) {
                    $count += $pipes % 2;
                }
            }
        }

        return $count;
    }

    /**
     * @return Grid<Tile>
     */
    public function constructPipeGrid(string $input): Grid
    {
        $gridValues = [];
        $start = null;

        foreach (explode("\n", $input) as $y => $row) {
            foreach (str_split($row) as $x => $char) {
                $gridValues[$y][$x] = Tile::from($char);

                if (Tile::START === $gridValues[$y][$x]) {
                    $start = new Point2D($x, $y);
                }
            }
        }
        /** @var Grid<Tile> $grid */
        $grid = new Grid($gridValues);

        /** @var Queue<Player> $queue */
        $queue = new Queue();

        /** @var Grid<Tile> $pipe */
        $pipe = new Grid();

        [$north, $east, $south, $west] = $start->adjacent();
        $startDirection1 = null;

        if (in_array($grid->get($north), [Tile::SOUTH_EAST, Tile::SOUTH_WEST, Tile::VERTICAL])) {
            $queue->push(new Player(position: $north, tile: $grid->get($north), steps: 1, from: CompassDirection::SOUTH));
            $pipe->set($north, $grid->get($north));
            $startDirection1 = CompassDirection::NORTH;
        } elseif (in_array($grid->get($south), [Tile::NORTH_EAST, Tile::NORTH_WEST, Tile::VERTICAL])) {
            $queue->push(new Player(position: $south, tile: $grid->get($south), steps: 1, from: CompassDirection::NORTH));
            $pipe->set($south, $grid->get($south));
            $startDirection1 = CompassDirection::SOUTH;
        } elseif (in_array($grid->get($east), [Tile::NORTH_WEST, Tile::SOUTH_WEST, Tile::HORIZONTAL])) {
            $queue->push(new Player(position: $east, tile: $grid->get($east), steps: 1, from: CompassDirection::WEST));
            $pipe->set($east, $grid->get($east));
            $startDirection1 = CompassDirection::EAST;
        } elseif (in_array($grid->get($west), [Tile::NORTH_EAST, Tile::SOUTH_EAST, Tile::HORIZONTAL])) {
            $queue->push(new Player(position: $west, tile: $grid->get($west), steps: 1, from: CompassDirection::EAST));
            $pipe->set($west, $grid->get($west));
            $startDirection1 = CompassDirection::WEST;
        }

        while (!$queue->isEmpty()) {
            $player = $queue->pop();

            if ($player->position->equals($start)) {
                $startTile = match ($startDirection1->value . $player->from->value) {
                    'NS', 'SN' => Tile::VERTICAL,
                    'EW', 'WE' => Tile::HORIZONTAL,
                    'NE', 'EN' => Tile::NORTH_EAST,
                    'NW', 'WN' => Tile::NORTH_WEST,
                    'SW', 'WS' => Tile::SOUTH_WEST,
                    'SE', 'ES' => Tile::SOUTH_EAST,
                    default => throw new \RuntimeException('Invalid direction'),
                };
                $pipe->set($start, $startTile);

                return $pipe;
            }

            $next = match ($player->tile) {
                Tile::HORIZONTAL => CompassDirection::EAST === $player->from ? $player->position->west() : $player->position->east(),
                Tile::VERTICAL => CompassDirection::NORTH === $player->from ? $player->position->south() : $player->position->north(),
                Tile::NORTH_EAST => CompassDirection::NORTH === $player->from ? $player->position->east() : $player->position->north(),
                Tile::NORTH_WEST => CompassDirection::NORTH === $player->from ? $player->position->west() : $player->position->north(),
                Tile::SOUTH_WEST => CompassDirection::SOUTH === $player->from ? $player->position->west() : $player->position->south(),
                Tile::SOUTH_EAST => CompassDirection::SOUTH === $player->from ? $player->position->east() : $player->position->south(),
                default => throw new \RuntimeException('Invalid tile'),
            };

            $nextFrom = match ($player->tile) {
                Tile::NORTH_EAST => CompassDirection::NORTH === $player->from ? CompassDirection::WEST : CompassDirection::SOUTH,
                Tile::NORTH_WEST => CompassDirection::NORTH === $player->from ? CompassDirection::EAST : CompassDirection::SOUTH,
                Tile::SOUTH_WEST => CompassDirection::SOUTH === $player->from ? CompassDirection::EAST : CompassDirection::NORTH,
                Tile::SOUTH_EAST => CompassDirection::SOUTH === $player->from ? CompassDirection::WEST : CompassDirection::NORTH,
                default => $player->from,
            };

            $queue->push(new Player(position: $next, tile: $grid->get($next), steps: $player->steps + 1, from: $nextFrom));
            $pipe->set($next, $grid->get($next));
        }

        throw new \RuntimeException('No solution found');
    }
}
