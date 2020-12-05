<?php

declare(strict_types=1);

namespace App\Event;

use Exception;

interface DayInterface
{
    public function testPart1(): iterable;
    public function testPart2(): iterable;

    /**
     * @param string $input
     *
     * @return string
     * @throws Exception
     */
    public function solvePart1(string $input): string;

    /**
     * @param string $input
     *
     * @return string
     * @throws Exception
     */
    public function solvePart2(string $input): string;
}
