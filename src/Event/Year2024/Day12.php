<?php

declare(strict_types=1);

namespace App\Event\Year2024;

use App\Event\DayInterface;
use App\Util\Delta;
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

            foreach ($region as $y => $xLine) {
                foreach ($xLine as $x => $type) {
                    $area++;
                    $point = new Point2D($x, $y);

                    foreach (Delta::EDGE_CAPS as $deltaList) {
                        $inside = count(array_filter($deltaList, fn($d) => isset($region[$point->y - $d[1]][$point->x - $d[0]])));
                        $cornerInside = isset($region[$point->y - $deltaList[1][1]][$point->x - $deltaList[1][0]]);

                        if (
                            0 === $inside // External corner (pointing outwards of the area)
                            || (1 === $inside && $cornerInside) // Edge case - external corner pointing to external corner of the same area diagonally
                            || (2 === $inside && !$cornerInside) // Internal corner (pointing inwards to the area)
                        ) {
                            $perimeter++;
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
