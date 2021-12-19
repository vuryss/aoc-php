<?php

declare(strict_types=1);

namespace App\Event\Year2021\Helpers;

class Beacon
{
    public array $distanceTo;
    public array $distances;

    public function __construct(
        public Point3 $coordinates
    ) {
    }
}
