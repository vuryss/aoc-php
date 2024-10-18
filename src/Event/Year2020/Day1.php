<?php

declare(strict_types=1);

namespace App\Event\Year2020;

use App\Event\DayInterface;
use Exception;

class Day1 implements DayInterface
{
    public function testPart1(): iterable
    {
        yield '514579' => <<<'INPUT'
            1721
            979
            366
            299
            675
            1456
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '241861950' => <<<'INPUT'
            1721
            979
            366
            299
            675
            1456
            INPUT;
    }

    public function solvePart1(string $input): string
    {
        /** @var list<int> $list */
        $list = explode("\n", $input);
        $map = array_fill_keys($list, true);

        foreach ($list as $entry) {
            if (isset($map[2020 - $entry])) {
                return (string) ($entry * (2020 - $entry));
            }
        }

        throw new Exception('Solution not found');
    }

    public function solvePart2(string $input): string
    {
        /** @var list<int> $list */
        $list = explode("\n", $input);
        $count = count($list);
        $map = array_fill_keys($list, true);

        for ($i = 0; $i < $count - 2; $i++) {
            for ($j = $i + 1; $j < $count - 1; $j++) {
                if (isset($map[2020 - $list[$i] - $list[$j]])) {
                    return (string) ($list[$i] * $list[$j] * (2020 - $list[$i] - $list[$j]));
                }
            }
        }

        throw new Exception('Solution not found');
    }
}
