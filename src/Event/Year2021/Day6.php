<?php

declare(strict_types=1);

namespace App\Event\Year2021;

use App\Event\DayInterface;

class Day6 implements DayInterface
{
    public function testPart1(): iterable
    {
        yield '5934' => <<<'INPUT'
            3,4,3,1,2
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '26984457539' => <<<'INPUT'
            3,4,3,1,2
            INPUT;
    }

    public function solvePart1(string $input): string
    {
        return (string) $this->countFishAfterDays($input, 80);
    }

    public function solvePart2(string $input): string
    {
        return (string) $this->countFishAfterDays($input, 256);
    }

    public function countFishAfterDays(string $input, int $days): int
    {
        $fish = array_map('intval', explode(',', $input));
        $fishByDay = array_fill_keys(range(0, 8), 0);

        foreach ($fish as $day) {
            $fishByDay[$day]++;
        }

        for ($i = 0; $i < $days; $i++) {
            foreach ($fishByDay as $day => $numberOfFish) {
                if ($day === 0) {
                    $fishByDay[8] += $numberOfFish;
                    $fishByDay[6] += $numberOfFish;
                    $fishByDay[0] -= $numberOfFish;
                } else {
                    $fishByDay[$day - 1] += $numberOfFish;
                    $fishByDay[$day] -= $numberOfFish;
                }
            }
        }

        return array_sum($fishByDay);
    }
}
