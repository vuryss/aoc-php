<?php

declare(strict_types=1);

namespace App\Event\Year2020;

use App\Event\DayInterface;

class Day25 implements DayInterface
{
    public function testPart1(): iterable
    {
        yield '14897079' => <<<'INPUT'
            5764801
            17807724
            INPUT;
    }

    public function testPart2(): iterable
    {
        return [];
    }

    public function solvePart1(string $input): string
    {
        [$cardPublicKey, $doorPublicKey] = array_map('intval', explode("\n", $input));
        $cardLoopSize = $doorLoopSize = null;

        $value = 1;
        $subjectNumber = 7;
        $loopId = 0;

        while ($cardLoopSize === null || $doorLoopSize === null) {
            $value *= $subjectNumber;
            $value %= 20201227;
            $loopId++;

            if ($cardPublicKey === $value) {
                $cardLoopSize = $loopId;
            }

            if ($doorPublicKey === $value) {
                $doorLoopSize = $loopId;
            }
        }

        $value = 1;
        $subjectNumber = $doorPublicKey;

        for ($i = 1; $i <= $cardLoopSize; $i++) {
            $value *= $subjectNumber;
            $value %= 20201227;
        }

        return (string) $value;
    }

    public function solvePart2(string $input): string
    {
        return '';
    }
}
