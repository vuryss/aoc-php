<?php

declare(strict_types=1);

namespace App\Util;

readonly class Algorithms
{
    /**
     * @param array<Point2D> $points
     */
    public static function shoelaceArea(array $points): int
    {
        $area = 0.0;
        $numberOfPoints = count($points);

        for ($i = 0; $i < $numberOfPoints - 1; $i++) {
            $area += $points[$i]->x * $points[$i + 1]->y - $points[$i + 1]->x * $points[$i]->y;
        }

        $area += $points[$numberOfPoints - 1]->x * $points[0]->y - $points[0]->x * $points[$numberOfPoints - 1]->y;
        $area = abs($area) / 2;

        return (int) $area;
    }

    public static function maxCliqueBronKerbosch(array $connections): array
    {
        uasort($connections, fn ($a, $b) => count($b) <=> count($a));
        $P = array_keys($connections);
        $maxClique = [];

        foreach (self::bronKerbosch($connections, [], $P, []) as $clique) {
            if (count($clique) > count($maxClique)) {
                $maxClique = $clique;
            }
        }

        return $maxClique;
    }

    private static function bronKerbosch(
        array $connections,
        array $clique = [],
        array $nodes = [],
        array $excluded = []
    ): iterable {
        if ([] === $nodes && [] === $excluded) {
            yield $clique;
            return;
        }

        $pivot = [] === $nodes ? reset($excluded) : reset($nodes);

        foreach (array_diff($nodes, $connections[$pivot]) as $node) {
            yield from self::bronKerbosch(
                $connections,
                [...$clique, $node],
                array_intersect($nodes, $connections[$node]),
                array_intersect($excluded, $connections[$node])
            );

            $nodes = array_diff($nodes, [$node]);
            $excluded[] = $node;
        }
    }
}
