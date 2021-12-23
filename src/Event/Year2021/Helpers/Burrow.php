<?php

declare(strict_types=1);

namespace App\Event\Year2021\Helpers;

class Burrow
{
    /** @var Point2[][] */
    private array $rooms = [];

    public function __construct(int $levels)
    {
        for ($level = 2; $level <= $levels; $level++) {
            $this->rooms['A'][] = new Point2(3, $level);
            $this->rooms['B'][] = new Point2(5, $level);
            $this->rooms['C'][] = new Point2(7, $level);
            $this->rooms['D'][] = new Point2(9, $level);
        }
    }

    public function isForbidden(Point2 $point): bool
    {
        return $point->y === 1
            && ($point->x === 3 || $point->x === 5 || $point->x === 7 || $point->x === 9);
    }

    public function isAmphipodTypeRoom(string $amphipodType, Point2 $point): bool
    {
        foreach ($this->rooms[$amphipodType] as $roomPosition) {
            if ($roomPosition->equals($point)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return Point2[]
     */
    public function getAmphipodTypeRooms(string $amphipodType): array
    {
        return $this->rooms[$amphipodType];
    }
}
