<?php

declare(strict_types=1);

namespace App\Event\Year2024;

use App\Event\DayInterface;
use App\Util\Point2D;
use App\Util\StringUtil;
use Ds\Queue;

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
        $regions = $this->collectRegions($input);
        $sum = 0;

        foreach ($regions as $region) {
            $area = $perimeter = 0;

            foreach ($region as $y => $xLine) {
                foreach ($xLine as $x => $type) {
                    $area++;

                    foreach (new Point2D($x, $y)->adjacent() as $adjacent) {
                        if (!isset($region[$adjacent->y][$adjacent->x])) {
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
        $regions = $this->collectRegions($input);
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
                        if (!isset($region[$adjacent->y][$adjacent->x])) {
                            $perimeterPoints[$point->y][$point->x][$side] = true;
                        }
                    }
                }
            }

            while ([] !== array_filter($perimeterPoints)) {
                foreach ($perimeterPoints as $y => $xLine) {
                    foreach ($xLine as $x => $sides) {
                        foreach (array_keys($sides) as $side) {
                            $perimeter++;
                            unset($perimeterPoints[$y][$x][$side]);
                            $dx = $side === 'N' || $side === 'S' ? 1 : 0;
                            $dy = $dx === 0 ? 1 : 0;

                            for ($x1 = $x + $dx, $y1 = $y + $dy; isset($perimeterPoints[$y1][$x1][$side]); $x1 += $dx, $y1 += $dy) {
                                unset($perimeterPoints[$y1][$x1][$side]);
                            }

                            for ($x1 = $x - $dx, $y1 = $y - $dy; isset($perimeterPoints[$y1][$x1][$side]); $x1 -= $dx, $y1 -= $dy) {
                                unset($perimeterPoints[$y1][$x1][$side]);
                            }

                            $perimeterPoints = array_map(array_filter(...), $perimeterPoints);
                            continue 4;
                        }
                    }
                }
            }

            $sum += $area * $perimeter;
        }

        return $sum;
    }

    private function collectRegions(string $input): array
    {
        $grid = StringUtil::inputToGridOfChars($input);
        $regions = [];

        while ([] !== array_filter($grid)) {
            foreach ($grid as $y => $line) {
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
                unset($grid[$point->y][$point->x]);

                foreach ($point->adjacent() as $p) {
                    if (($grid[$p->y][$p->x] ?? '') === $type && !isset($visited[$p->y][$p->x])) {
                        $queue->push([new Point2D($p->x, $p->y), $type, $visited]);
                    }
                }
            }

            $regions[] = $visited;
        }

        return $regions;
    }
}
