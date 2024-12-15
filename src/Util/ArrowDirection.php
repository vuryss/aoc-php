<?php

declare(strict_types=1);

namespace App\Util;

enum ArrowDirection: string
{
    case NORTH = '^';
    case EAST = '>';
    case SOUTH = 'v';
    case WEST = '<';

    public function fromPoint(Point2D $point): Point2D
    {
        return match ($this) {
            self::NORTH => $point->north(),
            self::EAST => $point->east(),
            self::SOUTH => $point->south(),
            self::WEST => $point->west(),
        };
    }
}
