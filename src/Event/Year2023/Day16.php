<?php

declare(strict_types=1);

namespace App\Event\Year2023;

use App\Event\DayInterface;
use App\Event\Year2023\Day16\Beam;
use App\Util\Point2D;
use App\Util\RelativeDirection;
use App\Util\StringUtil;
use Ds\Queue;

class Day16 implements DayInterface
{
    public function testPart1(): iterable
    {
        yield '46' => <<<'INPUT'
            .|...\....
            |.-.\.....
            .....|-...
            ........|.
            ..........
            .........\
            ..../.\\..
            .-.-/..|..
            .|....-|.\
            ..//.|....
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '51' => <<<'INPUT'
            .|...\....
            |.-.\.....
            .....|-...
            ........|.
            ..........
            .........\
            ..../.\\..
            .-.-/..|..
            .|....-|.\
            ..//.|....
            INPUT;
    }

    public function solvePart1(string $input): string|int
    {
        $grid = StringUtil::inputToGridOfChars($input);
        $beam = new Beam(new Point2D(-1, 0), RelativeDirection::RIGHT);

        return $this->countEnergizedTiles($beam, $grid);
    }

    public function solvePart2(string $input): string|int
    {
        $grid = StringUtil::inputToGridOfChars($input);
        $max = 0;
        $maxX = count($grid[0]);
        $maxY = count($grid);

        for ($x = 0; $x < $maxX; $x++) {
            $start = new Beam(new Point2D($x, -1), RelativeDirection::DOWN);
            $max = max($max, $this->countEnergizedTiles($start, $grid));

            $start = new Beam(new Point2D($x, $maxY), RelativeDirection::UP);
            $max = max($max, $this->countEnergizedTiles($start, $grid));
        }

        for ($y = 0; $y < $maxY; $y++) {
            $start = new Beam(new Point2D(-1, $y), RelativeDirection::RIGHT);
            $max = max($max, $this->countEnergizedTiles($start, $grid));

            $start = new Beam(new Point2D($maxX, $y), RelativeDirection::LEFT);
            $max = max($max, $this->countEnergizedTiles($start, $grid));
        }

        return $max;
    }

    private function countEnergizedTiles(Beam $beam, array $grid): int
    {
        /** @var Queue<Beam> $queue */
        $queue = new Queue();
        $queue->push($beam);

        $energized = [];
        $alreadySeenState = [];

        while (!$queue->isEmpty()) {
            $beam = $queue->pop();

            if (isset($alreadySeenState[$beam->position->y][$beam->position->x][$beam->direction->value])) {
                continue;
            }

            $energized[$beam->position->y][$beam->position->x] = true;
            $alreadySeenState[$beam->position->y][$beam->position->x][$beam->direction->value] = true;

            $next = match ($beam->direction) {
                RelativeDirection::UP => $beam->position->north(),
                RelativeDirection::DOWN => $beam->position->south(),
                RelativeDirection::LEFT => $beam->position->west(),
                RelativeDirection::RIGHT => $beam->position->east(),
            };

            if (!isset($grid[$next->y][$next->x])) {
                continue;
            }

            switch ($grid[$next->y][$next->x]) {
                case '.':
                    $queue->push(new Beam($next, $beam->direction));
                    break;

                case '|':
                    if (RelativeDirection::UP === $beam->direction || RelativeDirection::DOWN === $beam->direction) {
                        $queue->push(new Beam($next, $beam->direction));
                        break;
                    }

                    $queue->push(new Beam($next, RelativeDirection::UP));
                    $queue->push(new Beam($next, RelativeDirection::DOWN));
                    break;

                case '-':
                    if (RelativeDirection::LEFT === $beam->direction || RelativeDirection::RIGHT === $beam->direction) {
                        $queue->push(new Beam($next, $beam->direction));
                        break;
                    }

                    $queue->push(new Beam($next, RelativeDirection::LEFT));
                    $queue->push(new Beam($next, RelativeDirection::RIGHT));
                    break;

                case '/':
                    $newDirection = match ($beam->direction) {
                        RelativeDirection::UP => RelativeDirection::RIGHT,
                        RelativeDirection::DOWN => RelativeDirection::LEFT,
                        RelativeDirection::LEFT => RelativeDirection::DOWN,
                        RelativeDirection::RIGHT => RelativeDirection::UP,
                    };

                    $queue->push(new Beam($next, $newDirection));
                    break;

                case '\\':
                    $newDirection = match ($beam->direction) {
                        RelativeDirection::UP => RelativeDirection::LEFT,
                        RelativeDirection::DOWN => RelativeDirection::RIGHT,
                        RelativeDirection::LEFT => RelativeDirection::UP,
                        RelativeDirection::RIGHT => RelativeDirection::DOWN,
                    };

                    $queue->push(new Beam($next, $newDirection));
                    break;
            }
        }

        return count($energized, COUNT_RECURSIVE) - count($energized) - 1;
    }
}
