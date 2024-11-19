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
}
