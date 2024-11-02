<?php

declare(strict_types=1);

namespace App\Event\Year2023\Day10;

enum Tile: string
{
    case VERTICAL = '|';
    case HORIZONTAL = '-';
    case NORTH_EAST = 'L';
    case NORTH_WEST = 'J';
    case SOUTH_WEST = '7';
    case SOUTH_EAST = 'F';
    case GROUND = '.';
    case START = 'S';
}
