<?php

declare(strict_types=1);

namespace App\Event\Year2021\Helpers;

class Scanner
{
    public array $beacons = [];
    public Point3 $coordinates;

    public function __construct(
        public int $id,
    ) {
        $this->coordinates = new Point3();
    }

    public function calculateDistancesBetweenBeacons(): void
    {
        foreach ($this->beacons as $beaconIndex => $beacon) {
            foreach ($this->beacons as $beaconIndex2 => $beacon2) {
                if ($beaconIndex === $beaconIndex2 || isset($this->beacons[$beaconIndex]['distance'][$beaconIndex2])) {
                    continue;
                }

                $num = ($beacon2['coords']->x - $beacon['coords']->x) ** 2
                    + ($beacon2['coords']->y - $beacon['coords']->y) ** 2
                    + ($beacon2['coords']->z - $beacon['coords']->z) ** 2;

                $num = bcsqrt((string) $num, 2);

                $this->beacons[$beaconIndex]['distance'][$beaconIndex2] = $num;
                $this->beacons[$beaconIndex2]['distance'][$beaconIndex] = $num;
            }
        }
    }
}
