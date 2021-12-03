<?php

declare(strict_types=1);

namespace App\Event\Year2021;

use App\Event\DayInterface;

class Day2 implements DayInterface
{
    public function testPart1(): iterable
    {
        yield '150' => <<<'INPUT'
            forward 5
            down 5
            forward 8
            up 3
            down 8
            forward 2
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '900' => <<<'INPUT'
            forward 5
            down 5
            forward 8
            up 3
            down 8
            forward 2
            INPUT;
    }

    public function solvePart1(string $input): string
    {
        $commands = explode("\n", $input);
        $depth = $pos = 0;

        foreach ($commands as $command) {
            [$command, $value] = sscanf($command, '%s %d');

            match ($command) {
                'forward' => $pos += $value,
                'up' => $depth -= $value,
                'down' => $depth += $value,
            };
        }

        return (string) ($depth * $pos);
    }

    public function solvePart2(string $input): string
    {
        $commands = explode("\n", $input);
        $depth = $pos = $aim = 0;

        foreach ($commands as $command) {
            [$command, $value] = sscanf($command, '%s %d');
            [$depth, $pos, $aim] = match ($command) {
                'forward' => [$depth + $aim * $value, $pos + $value, $aim],
                'up' => [$depth, $pos, $aim - $value],
                'down' => [$depth, $pos, $aim + $value],
            };
        }

        return (string) ($depth * $pos);
    }
}
