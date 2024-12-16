<?php

declare(strict_types=1);

namespace App\Util;

enum CompassDirection: string
{
    case NORTH = 'N';
    case EAST = 'E';
    case SOUTH = 'S';
    case WEST = 'W';

    public function turnLeft(): CompassDirection
    {
        return match ($this) {
            self::NORTH => self::WEST,
            self::EAST => self::NORTH,
            self::SOUTH => self::EAST,
            self::WEST => self::SOUTH,
        };
    }

    public function turnRight(): CompassDirection
    {
        return match ($this) {
            self::NORTH => self::EAST,
            self::EAST => self::SOUTH,
            self::SOUTH => self::WEST,
            self::WEST => self::NORTH,
        };
    }

    public function movePoint(Point2D $point): Point2D
    {
        return match ($this) {
            self::NORTH => $point->north(),
            self::EAST => $point->east(),
            self::SOUTH => $point->south(),
            self::WEST => $point->west(),
        };
    }
}
