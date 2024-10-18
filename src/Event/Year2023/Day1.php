<?php

declare(strict_types=1);

namespace App\Event\Year2023;

use App\Event\DayInterface;

class Day1 implements DayInterface
{
    private const array WORDS_TO_DIGITS = [
        'zero' => 0,
        'one' => 1,
        'two' => 2,
        'three' => 3,
        'four' => 4,
        'five' => 5,
        'six' => 6,
        'seven' => 7,
        'eight' => 8,
        'nine' => 9,
    ];

    public function testPart1(): iterable
    {
        yield '142' => <<<'INPUT'
            1abc2
            pqr3stu8vwx
            a1b2c3d4e5f
            treb7uchet
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '281' => <<<'INPUT'
            two1nine
            eightwothree
            abcone2threexyz
            xtwone3four
            4nineeightseven2
            zoneight234
            7pqrstsixteen
            INPUT;
    }

    public function solvePart1(string $input): string|int
    {
        $lines = explode("\n", $input);
        $sum = 0;

        foreach ($lines as $line) {
            preg_match_all('/[0-9]/', $line, $matches);
            $numbers = $matches[0];
            $sum += (int) ($numbers[array_key_first($numbers)] . $numbers[array_key_last($numbers)]);
        }

        return $sum;
    }

    public function solvePart2(string $input): string|int
    {
        $lines = explode("\n", $input);
        $sum = 0;

        foreach ($lines as $line) {
            $numbers = array_filter(str_split($line), 'ctype_digit');

            foreach (self::WORDS_TO_DIGITS as $word => $digit) {
                if (false !== ($firstPosition = strpos($line, $word))) {
                    $numbers[$firstPosition] = $digit;
                }

                if (false !== ($lastPosition = strrpos($line, $word))) {
                    $numbers[$lastPosition] = $digit;
                }
            }

            $sum += (int) ($numbers[min(array_keys($numbers))] . $numbers[max(array_keys($numbers))]);
        }

        return $sum;
    }
}
