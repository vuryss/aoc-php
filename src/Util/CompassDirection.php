<?php

declare(strict_types=1);

namespace App\Util;

enum CompassDirection: string
{
    case NORTH = 'N';
    case EAST = 'E';
    case SOUTH = 'S';
    case WEST = 'W';
}
