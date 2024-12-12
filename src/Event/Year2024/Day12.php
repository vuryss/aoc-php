<?php

declare(strict_types=1);

namespace App\Event\Year2024;

use App\Event\DayInterface;
use App\Util\Point2D;
use App\Util\StringUtil;
use Ds\Queue;
use Nette\Utils\Arrays;

class Day12 implements DayInterface
{
    public function testPart1(): iterable
    {
        yield '140' => <<<'INPUT'
            AAAA
            BBCD
            BBCC
            EEEC
            INPUT;

        yield '772' => <<<'INPUT'
            OOOOO
            OXOXO
            OOOOO
            OXOXO
            OOOOO
            INPUT;

        yield '1930' => <<<'INPUT'
            RRRRIICCFF
            RRRRIICCCF
            VVRRRCCFFF
            VVRCCCJFFF
            VVVVCJJCFE
            VVIVCCJJEE
            VVIIICJJEE
            MIIIIIJJEE
            MIIISIJEEE
            MMMISSJEEE
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '80' => <<<'INPUT'
            AAAA
            BBCD
            BBCC
            EEEC
            INPUT;

        yield '436' => <<<'INPUT'
            OOOOO
            OXOXO
            OOOOO
            OXOXO
            OOOOO
            INPUT;

        yield '236' => <<<'INPUT'
            EEEEE
            EXXXX
            EEEEE
            EXXXX
            EEEEE
            INPUT;

        yield '368' => <<<'INPUT'
            AAAAAA
            AAABBA
            AAABBA
            ABBAAA
            ABBAAA
            AAAAAA
            INPUT;

        yield '1206' => <<<'INPUT'
            RRRRIICCFF
            RRRRIICCCF
            VVRRRCCFFF
            VVRCCCJFFF
            VVVVCJJCFE
            VVIVCCJJEE
            VVIIICJJEE
            MIIIIIJJEE
            MIIISIJEEE
            MMMISSJEEE
            INPUT;
    }

    public function solvePart1(string $input): string|int
    {
        $grid = StringUtil::inputToGridOfChars($input);
        $regions = $this->collectRegions($grid);
        $sum = 0;

        foreach ($regions as $region) {
            $area = $perimeter = 0;

            foreach ($region as $y => $xLine) {
                foreach ($xLine as $x => $type) {
                    $area++;

                    foreach (new Point2D($x, $y)->adjacent() as $adjacent) {
                        if (($grid[$adjacent->y][$adjacent->x] ?? '') !== $type) {
                            $perimeter++;
                        }
                    }
                }
            }

            $sum += $area * $perimeter;
        }

        return $sum;
    }

    public function solvePart2(string $input): string|int
    {
        $grid = StringUtil::inputToGridOfChars($input);
        $regions = $this->collectRegions($grid);
        $sum = 0;

        foreach ($regions as $region) {
            $area = $perimeter = 0;
            $perimeterPoints = [];

            foreach ($region as $y => $xLine) {
                foreach ($xLine as $x => $type) {
                    $area++;
                    $point = new Point2D($x, $y);
                    $adj = ['N' => $point->north(), 'E' => $point->east(), 'S' => $point->south(), 'W' => $point->west()];

                    foreach ($adj as $side => $adjacent) {
                        if (($grid[$adjacent->y][$adjacent->x] ?? '') !== $type) {
                            $perimeterPoints[$point->y][$point->x][$side] = true;
                        }
                    }
                }
            }

            while (!empty($perimeterPoints)) {
                foreach ($perimeterPoints as $y => $xLine) {
                    if (empty($perimeterPoints[$y])) {
                        unset($perimeterPoints[$y]);
                    }

                    foreach ($xLine as $x => $sides) {
                        if (empty($perimeterPoints[$y][$x])) {
                            unset($perimeterPoints[$y][$x]);
                        }

                        foreach (array_keys($sides) as $side) {
                            $perimeter++;
                            unset($perimeterPoints[$y][$x][$side]);

                            if ($side === 'N' || $side === 'S') {
                                for ($x1 = $x + 1; $x1 < count($grid[0]); $x1++) {
                                    if (!isset($perimeterPoints[$y][$x1][$side])) {
                                        break;
                                    }
                                    unset($perimeterPoints[$y][$x1][$side]);
                                }

                                for ($x1 = $x - 1; $x1 >= 0; $x1--) {
                                    if (!isset($perimeterPoints[$y][$x1][$side])) {
                                        break;
                                    }
                                    unset($perimeterPoints[$y][$x1][$side]);
                                }
                            }

                            if ($side === 'E' || $side === 'W') {
                                for ($y1 = $y + 1; $y1 < count($grid); $y1++) {
                                    if (!isset($perimeterPoints[$y1][$x][$side])) {
                                        break;
                                    }
                                    unset($perimeterPoints[$y1][$x][$side]);
                                }

                                for ($y1 = $y - 1; $y1 >= 0; $y1--) {
                                    if (!isset($perimeterPoints[$y1][$x][$side])) {
                                        break;
                                    }
                                    unset($perimeterPoints[$y1][$x][$side]);
                                }
                            }

                            continue 4;
                        }
                    }
                }
            }

            $sum += $area * $perimeter;
        }

        return $sum;
    }

    private function collectRegions(array $grid): array
    {
        $regions = [];
        $notVisited = $grid;

        while ([] !== array_filter($notVisited)) {
            foreach ($notVisited as $y => $line) {
                foreach ($line as $x => $char) {
                    break 2;
                }
            }

            $visited = [];
            $queue = new Queue([[new Point2D($x, $y), $char]]);

            while (!$queue->isEmpty()) {
                /** @var Point2D $point */
                [$point, $type] = $queue->pop();

                if (isset($visited[$point->y][$point->x])) {
                    continue;
                }

                $visited[$point->y][$point->x] = $type;
                unset($notVisited[$point->y][$point->x]);

                foreach ($point->adjacent() as $adjacent) {
                    if (
                        ($grid[$adjacent->y][$adjacent->x] ?? '') === $type
                        && !isset($visited[$adjacent->y][$adjacent->x])
                    ) {
                        $queue->push([new Point2D($adjacent->x, $adjacent->y), $type, $visited]);
                    }
                }
            }

            $regions[] = $visited;
        }

        return $regions;
    }
}
