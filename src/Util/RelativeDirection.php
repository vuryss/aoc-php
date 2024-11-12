<?php

declare(strict_types=1);

namespace App\Util;

enum RelativeDirection: string
{
    case LEFT = 'L';
    case RIGHT = 'R';
    case UP = 'U';
    case DOWN = 'D';
}
