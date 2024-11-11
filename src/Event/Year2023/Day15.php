<?php

declare(strict_types=1);

namespace App\Event\Year2023;

use App\Event\DayInterface;

class Day15 implements DayInterface
{
    public function testPart1(): iterable
    {
        yield '52' => <<<'INPUT'
            HASH
            INPUT;

        yield '1320' => <<<'INPUT'
            rn=1,cm-,qp=3,cm=2,qp-,pc=4,ot=9,ab=5,pc-,pc=6,ot=7
            INPUT;

    }

    public function testPart2(): iterable
    {
        yield '145' => <<<'INPUT'
            rn=1,cm-,qp=3,cm=2,qp-,pc=4,ot=9,ab=5,pc-,pc=6,ot=7
            INPUT;
    }

    public function solvePart1(string $input): string|int
    {
        $parts = explode(',', $input);
        $sum = 0;

        foreach ($parts as $part) {
            $sum += $this->hash($part);
        }

        return $sum;
    }

    public function solvePart2(string $input): string|int
    {
        $parts = explode(',', $input);
        $sum = 0;
        $boxes = [];

        foreach ($parts as $part) {
            preg_match('/^(\w+)([=-])(\d+)?$/', $part, $matches);
            $label = $matches[1];
            $boxNumber = $this->hash($label);
            $boxContent = $boxes[$boxNumber] ?? [];

            if ('-' === $matches[2]) {
                foreach ($boxContent as $key => $value) {
                    if ($value[0] === $label) {
                        unset($boxContent[$key]);
                    }
                }

                $boxes[$boxNumber] = array_values($boxContent);

                continue;
            }

            foreach ($boxContent as $key => $value) {
                if ($value[0] === $label) {
                    $boxes[$boxNumber][$key][1] = $matches[3];
                    continue 2;
                }
            }

            $boxes[$boxNumber][] = [$label, $matches[3]];
        }

        foreach ($boxes as $boxNumber => $contents) {
            foreach ($contents as $index => $lens) {
                $sum += (1 + $boxNumber) * (int) ($index + 1) * (int) $lens[1];
            }
        }

        return $sum;
    }

    private function hash(string $string): int
    {
        $hash = 0;
        $chars = str_split($string);

        foreach ($chars as $char) {
            $hash += ord($char);
            $hash *= 17;
            $hash %= 256;
        }

        return $hash;
    }
}
