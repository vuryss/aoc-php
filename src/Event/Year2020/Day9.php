<?php

declare(strict_types=1);

namespace App\Event\Year2020;

use App\Event\DayInterface;
use Exception;

class Day9 implements DayInterface
{
    private int $preamble = 25;

    public function testPart1(): iterable
    {
        $this->preamble = 5;

        yield '127' => <<<'INPUT'
            35
            20
            15
            25
            47
            40
            62
            55
            65
            95
            102
            117
            150
            182
            127
            219
            299
            277
            309
            576
            INPUT;
    }

    public function testPart2(): iterable
    {
        $this->preamble = 5;

        yield '62' => <<<'INPUT'
            35
            20
            15
            25
            47
            40
            62
            55
            65
            95
            102
            117
            150
            182
            127
            219
            299
            277
            309
            576
            INPUT;
    }

    public function solvePart1(string $input): string
    {
        $numbers = explode("\n", $input);

        return (string) $this->getInvalidNumber($numbers);
    }

    public function solvePart2(string $input): string
    {
        $numbers = explode("\n", $input);
        $countNumbers = count($numbers);

        $targetSum = $this->getInvalidNumber($numbers);

        $pointer = 0;

        while ($pointer < $countNumbers) {
            $sum = 0;
            $range = [];
            $pointer2 = $pointer;

            while ($sum < $targetSum) {
                $range[] = $numbers[$pointer2];
                $sum += $numbers[$pointer2++];
            }

            if ($sum === $targetSum) {
                return (string) (min($range) + max($range));
            }

            $pointer++;
        }

        throw new Exception('Not found');
    }

    /**
     * @param array $numbers
     *
     * @return int
     * @throws Exception
     */
    private function getInvalidNumber(array $numbers): int
    {
        $lastNumbers = array_splice($numbers, 0, $this->preamble);

        do {
            $number = (int) array_shift($numbers);

            $valid = false;

            foreach ($lastNumbers as $key => $value) {
                foreach ($lastNumbers as $key2 => $value2) {
                    if ($key === $key2) continue;
                    if ($value + $value2 === $number) {
                        $valid = true;
                        break 2;
                    }
                }
            }

            if (!$valid) {
                return $number;
            }

            array_shift($lastNumbers);
            array_push($lastNumbers, $number);
        } while (count($numbers) > 0);

        throw new Exception('Not found');
    }
}
