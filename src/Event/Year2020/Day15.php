<?php

declare(strict_types=1);

namespace App\Event\Year2020;

use App\Event\DayInterface;

class Day15 implements DayInterface
{
    public function testPart1(): iterable
    {
        yield '436' => '0,3,6';
        yield '1' => '1,3,2';
        yield '10' => '2,1,3';
        yield '27' => '1,2,3';
        yield '78' => '2,3,1';
        yield '438' => '3,2,1';
        yield '1836' => '3,1,2';
    }

    public function testPart2(): iterable
    {
        yield '175594' => '0,3,6';
        yield '2578' => '1,3,2';
        yield '3544142' => '2,1,3';
        yield '261214' => '1,2,3';
        yield '6895259' => '2,3,1';
        yield '18' => '3,2,1';
        yield '362' => '3,1,2';
    }

    public function solvePart1(string $input): string
    {
        $list = array_map('intval', explode(',', $input));

        return (string) $this->calculateNthNumberInSequence($list, 2020);
    }

    public function solvePart2(string $input): string
    {
        $list = array_map('intval', explode(',', $input));

        return (string) $this->calculateNthNumberInSequence($list, 30000000);
    }

    private function calculateNthNumberInSequence(array $start, int $number): int
    {
        $memory = [];

        foreach ($start as $index => $value) {
            $memory[$value] = $index + 1;
        }

        $last = $value;
        unset($memory[$last]);

        for ($turn = count($start); $turn < $number; $turn++) {
            $temp = $last;
            $last = $turn - ($memory[$last] ?? $turn);
            $memory[$temp] = $turn;
        }

        return $last;
    }
}
