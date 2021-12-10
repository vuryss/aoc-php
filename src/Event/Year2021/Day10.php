<?php

declare(strict_types=1);

namespace App\Event\Year2021;

use App\Event\DayInterface;

class Day10 implements DayInterface
{
    private const SCORE_INVALID = [
        ')' => 3,
        ']' => 57,
        '}' => 1197,
        '>' => 25137,
    ];

    private const SCORE_INCOMPLETE = [
        ')' => 1,
        ']' => 2,
        '}' => 3,
        '>' => 4,
    ];

    private const BRACKETS = [
        '(' => ')',
        '[' => ']',
        '{' => '}',
        '<' => '>',
    ];

    public function testPart1(): iterable
    {
        yield '26397' => <<<'INPUT'
            [({(<(())[]>[[{[]{<()<>>
            [(()[<>])]({[<{<<[]>>(
            {([(<{}[<>[]}>{[]{[(<()>
            (((({<>}<{<{<>}{[]{[]{}
            [[<[([]))<([[{}[[()]]]
            [{[{({}]{}}([{[{{{}}([]
            {<[[]]>}<{[{[{[]{()[[[]
            [<(<(<(<{}))><([]([]()
            <{([([[(<>()){}]>(<<{{
            <{([{{}}[<[[[<>{}]]]>[]]
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '288957' => <<<'INPUT'
            [({(<(())[]>[[{[]{<()<>>
            [(()[<>])]({[<{<<[]>>(
            {([(<{}[<>[]}>{[]{[(<()>
            (((({<>}<{<{<>}{[]{[]{}
            [[<[([]))<([[{}[[()]]]
            [{[{({}]{}}([{[{{{}}([]
            {<[[]]>}<{[{[{[]{()[[[]
            [<(<(<(<{}))><([]([]()
            <{([([[(<>()){}]>(<<{{
            <{([{{}}[<[[[<>{}]]]>[]]
            INPUT;
    }

    public function solvePart1(string $input): string
    {
        $lines = array_map('str_split', explode("\n", $input));
        $invalid = [];

        foreach ($lines as $chars) {
            [$invalid[],] = $this->parseLine($chars);
        }

        $sum = array_reduce($invalid, fn ($score, $char) => $char ? $score + self::SCORE_INVALID[$char] : $score,0);

        return (string) $sum;
    }

    public function solvePart2(string $input): string
    {
        $lines = array_map('str_split', explode("\n", $input));
        $score = [];

        foreach ($lines as $chars) {
            [, $missing] = $this->parseLine($chars);

            $score[] = array_reduce($missing, fn ($score, $char) => $score * 5 + self::SCORE_INCOMPLETE[$char], 0);
        }

        $score = array_filter($score);
        sort($score);

        return (string) $score[(int) floor(count($score) / 2)];
    }

    private function parseLine(array $chars): array
    {
        $stack = $missing = [];

        foreach ($chars as $char) {
            if (isset(self::BRACKETS[$char])) {
                $stack[] = $char;
            } elseif (in_array($char, self::BRACKETS, true)) {
                if ($char !== self::BRACKETS[$stack[array_key_last($stack)]]) {
                    return [$char, $missing];
                }
                array_pop($stack);
            }
        }

        while ($char = array_pop($stack)) {
            $missing[] = self::BRACKETS[$char];
        }

        return [null, $missing];
    }
}
