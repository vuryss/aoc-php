<?php

declare(strict_types=1);

namespace App\Event\Year2020;

use App\Event\DayInterface;

class Day19 implements DayInterface
{
    public function testPart1(): iterable
    {
        yield '2' => <<<'INPUT'
            0: 4 1 5
            1: 2 3 | 3 2
            2: 4 4 | 5 5
            3: 4 5 | 5 4
            4: "a"
            5: "b"
            
            ababbb
            bababa
            abbbab
            aaabbb
            aaaabbb
            INPUT;

        yield '3' => <<<'INPUT'
            42: 9 14 | 10 1
            9: 14 27 | 1 26
            10: 23 14 | 28 1
            1: "a"
            11: 42 31
            5: 1 14 | 15 1
            19: 14 1 | 14 14
            12: 24 14 | 19 1
            16: 15 1 | 14 14
            31: 14 17 | 1 13
            6: 14 14 | 1 14
            2: 1 24 | 14 4
            0: 8 11
            13: 14 3 | 1 12
            15: 1 | 14
            17: 14 2 | 1 7
            23: 25 1 | 22 14
            28: 16 1
            4: 1 1
            20: 14 14 | 1 15
            3: 5 14 | 16 1
            27: 1 6 | 14 18
            14: "b"
            21: 14 1 | 1 14
            25: 1 1 | 1 14
            22: 14 14
            8: 42
            26: 14 22 | 1 20
            18: 15 15
            7: 14 5 | 1 21
            24: 14 1
            
            abbbbbabbbaaaababbaabbbbabababbbabbbbbbabaaaa
            bbabbbbaabaabba
            babbbbaabbbbbabbbbbbaabaaabaaa
            aaabbbbbbaaaabaababaabababbabaaabbababababaaa
            bbbbbbbaaaabbbbaaabbabaaa
            bbbababbbbaaaaaaaabbababaaababaabab
            ababaaaaaabaaab
            ababaaaaabbbaba
            baabbaaaabbaaaababbaababb
            abbbbabbbbaaaababbbbbbaaaababb
            aaaaabbaabaaaaababaa
            aaaabbaaaabbaaa
            aaaabbaabbaaaaaaabbbabbbaaabbaabaaa
            babaaabbbaaabaababbaabababaaab
            aabbbbbaabbbaaaaaabbbbbababaaaaabbaaabba
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '12' => <<<'INPUT'
            42: 9 14 | 10 1
            9: 14 27 | 1 26
            10: 23 14 | 28 1
            1: "a"
            11: 42 31
            5: 1 14 | 15 1
            19: 14 1 | 14 14
            12: 24 14 | 19 1
            16: 15 1 | 14 14
            31: 14 17 | 1 13
            6: 14 14 | 1 14
            2: 1 24 | 14 4
            0: 8 11
            13: 14 3 | 1 12
            15: 1 | 14
            17: 14 2 | 1 7
            23: 25 1 | 22 14
            28: 16 1
            4: 1 1
            20: 14 14 | 1 15
            3: 5 14 | 16 1
            27: 1 6 | 14 18
            14: "b"
            21: 14 1 | 1 14
            25: 1 1 | 1 14
            22: 14 14
            8: 42
            26: 14 22 | 1 20
            18: 15 15
            7: 14 5 | 1 21
            24: 14 1
            
            abbbbbabbbaaaababbaabbbbabababbbabbbbbbabaaaa
            bbabbbbaabaabba
            babbbbaabbbbbabbbbbbaabaaabaaa
            aaabbbbbbaaaabaababaabababbabaaabbababababaaa
            bbbbbbbaaaabbbbaaabbabaaa
            bbbababbbbaaaaaaaabbababaaababaabab
            ababaaaaaabaaab
            ababaaaaabbbaba
            baabbaaaabbaaaababbaababb
            abbbbabbbbaaaababbbbbbaaaababb
            aaaaabbaabaaaaababaa
            aaaabbaaaabbaaa
            aaaabbaabbaaaaaaabbbabbbaaabbaabaaa
            babaaabbbaaabaababbaabababaaab
            aabbbbbaabbbaaaaaabbbbbababaaaaabbaaabba
            INPUT;
    }

    public function solvePart1(string $input): string
    {
        [$rulesDefinitions, $messages] = explode("\n\n", $input);

        $rules = $this->extractRulesFromDefinitions($rulesDefinitions);

        $rules = $this->convertRulesToRegexRules($rules);

        return (string) $this->countMatchesByRegex($messages, $rules[0]);
    }

    public function solvePart2(string $input): string
    {
        [$rulesDefinitions, $messages] = explode("\n\n", $input);

        $rules = $this->extractRulesFromDefinitions($rulesDefinitions);

        $rules[8] = [[42], [42, 8]];
        $rules[11] = [[42, 31], [42, 11, 31]];

        $rules = $this->convertRulesToRegexRules($rules);

        $rules[8] = $rules[8][0][0] . '+';
        $rules[11] = '(?P<group>(' . $rules[11][0][0] . ')(?P>group)?(' . $rules[11][0][1] . '))';
        $masterRule = $rules[8] . $rules[11];

        return (string) $this->countMatchesByRegex($messages, $masterRule);
    }

    private function extractRulesFromDefinitions(string $rulesDefinitions): array
    {
        $rules = [];

        foreach (explode("\n", $rulesDefinitions) as $ruleDefinition) {
            [$number, $definition] = explode(': ', $ruleDefinition);
            $variants = explode(' | ', $definition);

            foreach ($variants as $variant) {
                $variant = trim($variant, '"');
                $rules[$number][] = explode(' ', $variant);
            }
        }

        return $rules;
    }

    private function convertRulesToRegexRules(array $rules): array
    {
        $hasResolutions = true;
        $resolved = [];

        while ($hasResolutions) {
            $hasResolutions = false;

            foreach ($rules as $key => $rule) {
                $variants = [];

                foreach ($rule as $variant) {
                    foreach ($variant as $ruleNumber) {
                        if (is_numeric($ruleNumber)) continue 3;
                    }

                    $variants[] = implode('', $variant);
                }

                $resolved[$key] = implode('|', $variants);

                unset($rules[$key]);
            }

            foreach ($rules as $key => $rule) {
                foreach ($rule as $key2 => $variant) {
                    foreach ($variant as $key3 => $number) {
                        if (isset($resolved[$number])) {
                            $hasResolutions = true;
                            $rules[$key][$key2][$key3] = '(' . $resolved[$number] . ')';
                        }
                    }
                }
            }
        }

        return isset($resolved[0]) ? $resolved : $rules;
    }

    private function countMatchesByRegex(string $messages, string $regex): int
    {
        $count = 0;

        foreach (explode("\n", $messages) as $message) {
            if (preg_match('/^' . $regex . '$/', $message) === 1) {
                $count++;
            }
        }

        return  $count;
    }
}
