<?php

declare(strict_types=1);

namespace App\Util;

enum RelativeDirection: string
{
    case LEFT = 'L';
    case RIGHT = 'R';
    case UP = 'U';
    case DOWN = 'D';

    public function turnLeft(): self
    {
        return match ($this) {
            self::LEFT => self::DOWN,
            self::RIGHT => self::UP,
            self::UP => self::LEFT,
            self::DOWN => self::RIGHT,
        };
    }

    public function turnRight(): self
    {
        return match ($this) {
            self::LEFT => self::UP,
            self::RIGHT => self::DOWN,
            self::UP => self::RIGHT,
            self::DOWN => self::LEFT,
        };
    }
}
