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
        $P = array_keys($connections);
        $maxClique = [];

        foreach (self::bronKerbosch($connections, [], $P, []) as $clique) {
            if (count($clique) > count($maxClique)) {
                $maxClique = $clique;
            }
        }

        return $maxClique;
    }

    private static function bronKerbosch(array $connections, array $R = [], array $P = [], array $X = []): iterable
    {
        if ([] === $P && [] === $X) {
            yield $R;
            return;
        }

        $u = [] === $P ? reset($X) : reset($P);

        foreach (array_diff($P, $connections[$u]) as $vertex) {
            $R2 = array_merge($R, [$vertex]);
            $neighbors = $connections[$vertex];
            $P2 = array_intersect($P, $neighbors);
            $X2 = array_intersect($X, $neighbors);

            foreach (self::bronKerbosch($connections, $R2, $P2, $X2) as $clique) {
                yield $clique;
            }

            $P = array_diff($P, [$vertex]);
            $X = array_merge($X, [$vertex]);
        }
    }
}
