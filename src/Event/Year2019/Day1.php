<?php

declare(strict_types=1);

namespace App\Event\Year2019;

use App\Event\DayInterface;

class Day1 implements DayInterface
{
    public function testPart1(): iterable
    {
        yield '2' => '12';
        yield '2' => '14';
        yield '654' => '1969';
        yield '33583' => '100756';
    }

    public function testPart2(): iterable
    {
        yield '2' => '14';
        yield '966' => '1969';
        yield '50346' => '100756';
    }

    public function solvePart1(string $input): string
    {
        $list = explode("\n", $input);
        $sum = 0;

        foreach ($list as $entry) {
            $sum += (int) ($entry / 3) - 2;
        }

        return (string) $sum;
    }

    public function solvePart2(string $input): string
    {
        $list = explode("\n", $input);
        $sum = 0;

        foreach ($list as $entry) {
            do {
                $entry = (int) ($entry / 3) - 2;
                $sum += $entry > 0 ? $entry : 0;
            } while ($entry > 0);
        }

        return (string) $sum;
    }
}
