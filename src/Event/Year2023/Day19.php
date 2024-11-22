<?php

declare(strict_types=1);

namespace App\Event\Year2023;

use App\Event\DayInterface;
use App\Event\Year2023\Day19\Condition;
use App\Event\Year2023\Day19\Operator;
use App\Event\Year2023\Day19\Rule;
use App\Util\Range;

class Day19 implements DayInterface
{
    public function testPart1(): iterable
    {
        yield '19114' => <<<'INPUT'
            px{a<2006:qkq,m>2090:A,rfg}
            pv{a>1716:R,A}
            lnx{m>1548:A,A}
            rfg{s<537:gd,x>2440:R,A}
            qs{s>3448:A,lnx}
            qkq{x<1416:A,crn}
            crn{x>2662:A,R}
            in{s<1351:px,qqz}
            qqz{s>2770:qs,m<1801:hdj,R}
            gd{a>3333:R,R}
            hdj{m>838:A,pv}
            
            {x=787,m=2655,a=1222,s=2876}
            {x=1679,m=44,a=2067,s=496}
            {x=2036,m=264,a=79,s=2244}
            {x=2461,m=1339,a=466,s=291}
            {x=2127,m=1623,a=2188,s=1013}
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '167409079868000' => <<<'INPUT'
            px{a<2006:qkq,m>2090:A,rfg}
            pv{a>1716:R,A}
            lnx{m>1548:A,A}
            rfg{s<537:gd,x>2440:R,A}
            qs{s>3448:A,lnx}
            qkq{x<1416:A,crn}
            crn{x>2662:A,R}
            in{s<1351:px,qqz}
            qqz{s>2770:qs,m<1801:hdj,R}
            gd{a>3333:R,R}
            hdj{m>838:A,pv}
            
            {x=787,m=2655,a=1222,s=2876}
            {x=1679,m=44,a=2067,s=496}
            {x=2036,m=264,a=79,s=2244}
            {x=2461,m=1339,a=466,s=291}
            {x=2127,m=1623,a=2188,s=1013}
            INPUT;
    }

    public function solvePart1(string $input): string|int
    {
        $inputParts = explode("\n\n", $input);
        $workflows = $this->parseWorkflows($inputParts[0]);
        $parts = [];
        $accepted = [];

        foreach (explode("\n", $inputParts[1]) as $line) {
            $categories = explode(',', trim($line, '{}'));
            $parts[] = [
                'x' => (int) substr($categories[0], 2),
                'm' => (int) substr($categories[1], 2),
                'a' => (int) substr($categories[2], 2),
                's' => (int) substr($categories[3], 2),
            ];
        }

        foreach ($parts as $part) {
            $workflowName = 'in';

            while ($workflowName !== 'A' && $workflowName !== 'R') {
                foreach ($workflows[$workflowName] as $rule) {
                    $condition = $rule->condition;

                    if (
                        $condition === null
                        || ($condition->operator === Operator::LESS_THAN && $part[$condition->category] < $condition->value)
                        || ($condition->operator === Operator::GREATER_THAN && $part[$condition->category] > $condition->value)
                    ) {
                        $workflowName = $rule->destination;
                        break;
                    }
                }

                if ($workflowName === 'A') {
                    $accepted[] = $part;
                }
            }
        }

        $sum = 0;

        foreach ($accepted as $part) {
            $sum += $part['x'] + $part['m'] + $part['a'] + $part['s'];
        }

        return $sum;
    }

    public function solvePart2(string $input): string|int
    {
        $inputParts = explode("\n\n", $input);
        $workflows = $this->parseWorkflows($inputParts[0]);

        $categoryRanges = [
            'a' => new Range(1, 4000),
            'm' => new Range(1, 4000),
            's' => new Range(1, 4000),
            'x' => new Range(1, 4000),
        ];

        return $this->count($categoryRanges, 'in', $workflows);
    }

    /**
     * @return array<string, array<Rule>>
     */
    private function parseWorkflows(string $input): array
    {
        $workflows = [];
        $lines = explode("\n", $input);

        foreach ($lines as $line) {
            $name = substr($line, 0, strpos($line, '{'));
            $rulesString = trim(substr($line, strpos($line, '{') + 1), '{}');
            $rulesStringList = explode(',', $rulesString);
            $lastRule = array_pop($rulesStringList);

            foreach ($rulesStringList as $ruleString) {
                [$condition, $destination] = explode(':', $ruleString);
                $workflows[$name][] = new Rule(
                    destination: $destination,
                    condition: new Condition(
                        category: $condition[0],
                        operator: Operator::from($condition[1]),
                        value: (int) substr($condition, 2),
                    ),
                );
            }

            $workflows[$name][] = new Rule(destination: $lastRule);
        }

        return $workflows;
    }

    /**
     * @psalm-type CategoryRanges = array{a: Range, m: Range, s: Range, x: Range}
     *
     * @param CategoryRanges $categoryRanges
     * @param string $name
     * @param array<string, array<Rule>> $workflows
     * @return int
     */
    private function count(array $categoryRanges, string $name, array $workflows): int
    {
        if ($name === 'A') {
            return $categoryRanges['a']->numberOfItems() * $categoryRanges['m']->numberOfItems() * $categoryRanges['s']->numberOfItems() * $categoryRanges['x']->numberOfItems();
        }

        if ($name === 'R') {
            return 0;
        }

        $count = 0;
        $unmatched = $categoryRanges;

        foreach ($workflows[$name] as $rule) {
            $condition = $rule->condition;

            if ($condition === null) {
                $count += $this->count($unmatched, $rule->destination, $workflows);
                continue;
            }

            assert(array_key_exists($condition->category, $categoryRanges));
            $matched = $unmatched;

            if ($condition->operator === Operator::LESS_THAN) {
                $matched[$condition->category] = $matched[$condition->category]->intersect(new Range(0, $condition->value - 1));
                $unmatched[$condition->category] = $unmatched[$condition->category]->intersect(new Range($condition->value, 4000));
            } elseif ($condition->operator === Operator::GREATER_THAN) {
                $matched[$condition->category] = $matched[$condition->category]->intersect(new Range($condition->value + 1, 4000));
                $unmatched[$condition->category] = $unmatched[$condition->category]->intersect(new Range(0, $condition->value));
            }

            $count += $this->count($matched, $rule->destination, $workflows);
        }

        return $count;
    }
}
