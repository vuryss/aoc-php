<?php

declare(strict_types=1);

namespace App\Event\Year2023\Day16;

use App\Util\Point2D;
use App\Util\RelativeDirection;

readonly class Beam
{
    public function __construct(
        public Point2D $position,
        public RelativeDirection $direction,
    ) {
    }
}
