<?php

declare(strict_types=1);

namespace App\Event\Year2023\Day10;

use App\Util\CompassDirection;
use App\Util\Point2D;

readonly class Player
{
    public function __construct(
        public Point2D $position,
        public Tile $tile,
        public int $steps,
        public CompassDirection $from,
    ) {
    }
}
