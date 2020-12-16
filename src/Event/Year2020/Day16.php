<?php

declare(strict_types=1);

namespace App\Event\Year2020;

use App\Event\DayInterface;

class Day16 implements DayInterface
{
    public function testPart1(): iterable
    {
        yield '71' => <<<'INPUT'
            class: 1-3 or 5-7
            row: 6-11 or 33-44
            seat: 13-40 or 45-50
            
            your ticket:
            7,1,14
            
            nearby tickets:
            7,3,47
            40,4,50
            55,2,20
            38,6,12
            INPUT;
    }

    public function testPart2(): iterable
    {
        return [];
    }

    public function solvePart1(string $input): string
    {
        $data = $this->parseInput($input);
        $invalid = [];

        foreach ($data['nearbyTickets'] as $nearbyTicket) {
            foreach ($nearbyTicket as $value) {
                $matchingFields = $this->getWhichFieldsMatchValue($data['rules'], $value);

                if (count($matchingFields) === 0) {
                    $invalid[] = $value;
                }
            }
        }

        return (string) array_sum($invalid);
    }

    public function solvePart2(string $input): string
    {
        $data = $this->parseInput($input);

        foreach ($data['nearbyTickets'] as $ticketIndex => $nearbyTicket) {
            foreach ($nearbyTicket as $value) {
                $matchingFields = $this->getWhichFieldsMatchValue($data['rules'], $value);

                if (count($matchingFields) === 0) {
                    unset($data['nearbyTickets'][$ticketIndex]);
                    break;
                }
            }
        }

        $typeMap = [];
        $index = 0;
        $numberOfFields = count($data['rules']);

        while (true) {
            $matching = [];

            foreach ($data['nearbyTickets'] as $nearbyTicket) {
                $value = $nearbyTicket[$index];
                $matching[] = $this->getWhichFieldsMatchValue($data['rules'], $value);
            }

            $matchedFieldTypes = array_intersect(...$matching);

            if (count($matchedFieldTypes) === 1) {
                $typeMap[$index] = current($matchedFieldTypes);
                unset($data['rules'][$typeMap[$index]]);

                if (count($data['rules']) === 0) {
                    break;
                }
            }

            do {
                if (++$index >= $numberOfFields) {
                    $index = 0;
                }
            } while (isset($typeMap[$index]));
        }

        $product = [];

        foreach ($typeMap as $index => $value) {
            if (strpos($value, 'departure') !== false) {
                $product[] = $data['myTicket'][$index];
            }
        }

        return (string) array_product($product);
    }

    /**
     * @param array<string, array<string, int>> $rules
     * @param int $value
     *
     * @return string[]
     */
    private function getWhichFieldsMatchValue(array $rules, int $value): array
    {
        $matching = [];

        foreach ($rules as $name => $constraints) {
            foreach ($constraints as $constraint) {
                if ($value >= $constraint['min'] && $value <= $constraint['max']) {
                    $matching[] = $name;
                    continue 2;
                }
            }
        }

        return $matching;
    }

    private function parseInput(string $input): array
    {
        [$ruleList, $myTicketData, $nearbyTicketsData] = explode("\n\n", $input);

        $rules = [];

        foreach (explode("\n", $ruleList) as $ruleItem) {
            [$name] = explode(':', $ruleItem);
            preg_match_all('/(\d+)-(\d+)/', $ruleItem, $matches);

            foreach (array_keys($matches[0]) as $index) {
                $rules[$name][] = ['min' => $matches[1][$index], 'max' => $matches[2][$index]];
            }
        }

        $myTicket = array_map('intval', explode(',', explode("\n", $myTicketData)[1]));

        $nearbyTicketsData = explode("\n", $nearbyTicketsData);
        array_shift($nearbyTicketsData);

        $nearbyTickets = array_map(
            fn ($line) => array_map('intval', explode(',', $line)),
            $nearbyTicketsData
        );

        return [
            'rules' => $rules,
            'myTicket' => $myTicket,
            'nearbyTickets' => $nearbyTickets,
        ];
    }
}
