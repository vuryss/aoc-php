<?php

declare(strict_types=1);

namespace App\Event\Year2022;

use App\Event\DayInterface;

class Day1 implements DayInterface
{
    public function testPart1(): iterable
    {
        yield '24000' => <<<'INPUT'
            1000
            2000
            3000
            
            4000
            
            5000
            6000
            
            7000
            8000
            9000
            
            10000
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '45000' => <<<'INPUT'
            1000
            2000
            3000
            
            4000
            
            5000
            6000
            
            7000
            8000
            9000
            
            10000
            INPUT;
    }

    public function solvePart1(string $input): string|int
    {
        $blocks = explode("\n\n", $input);

        return (int) max(array_map(fn($block) => array_sum(explode("\n", $block)), $blocks));
    }

    public function solvePart2(string $input): string|int
    {
        $blocks = explode("\n\n", $input);
        $blocks = array_map(fn($block) => (int) array_sum(explode("\n", $block)), $blocks);
        rsort($blocks);

        return $blocks[0] + $blocks[1] + $blocks[2];
    }
}
