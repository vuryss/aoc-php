<?php

declare(strict_types=1);

namespace App\Event\Year2021\Helpers;

class Scanner
{
    /** @var Beacon[] */
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
                if ($beaconIndex === $beaconIndex2 || isset($this->beacons[$beaconIndex]->distanceTo[$beaconIndex2])) {
                    continue;
                }

                $num = ($beacon2->coordinates->x - $beacon->coordinates->x) ** 2
                    + ($beacon2->coordinates->y - $beacon->coordinates->y) ** 2
                    + ($beacon2->coordinates->z - $beacon->coordinates->z) ** 2;

                $num = bcsqrt((string) $num, 2);

                $this->beacons[$beaconIndex]->distanceTo[$beaconIndex2] = $num;
                $this->beacons[$beaconIndex2]->distanceTo[$beaconIndex] = $num;
                $this->beacons[$beaconIndex]->distances[$num] = $beaconIndex2;
                $this->beacons[$beaconIndex2]->distances[$num] = $beaconIndex;
            }
        }
    }
}
