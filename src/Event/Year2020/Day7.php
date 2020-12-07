<?php

declare(strict_types=1);

namespace App\Event\Year2020;

use App\Event\DayInterface;
use App\Event\Year2020\Helper\BagRequirements;
use SplQueue;

class Day7 implements DayInterface
{
    const OUR_BAG = 'shiny gold';

    public function testPart1(): iterable
    {
        yield '4' => <<<'INPUT'
            light red bags contain 1 bright white bag, 2 muted yellow bags.
            dark orange bags contain 3 bright white bags, 4 muted yellow bags.
            bright white bags contain 1 shiny gold bag.
            muted yellow bags contain 2 shiny gold bags, 9 faded blue bags.
            shiny gold bags contain 1 dark olive bag, 2 vibrant plum bags.
            dark olive bags contain 3 faded blue bags, 4 dotted black bags.
            vibrant plum bags contain 5 faded blue bags, 6 dotted black bags.
            faded blue bags contain no other bags.
            dotted black bags contain no other bags.
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '32' => <<<'INPUT'
            light red bags contain 1 bright white bag, 2 muted yellow bags.
            dark orange bags contain 3 bright white bags, 4 muted yellow bags.
            bright white bags contain 1 shiny gold bag.
            muted yellow bags contain 2 shiny gold bags, 9 faded blue bags.
            shiny gold bags contain 1 dark olive bag, 2 vibrant plum bags.
            dark olive bags contain 3 faded blue bags, 4 dotted black bags.
            vibrant plum bags contain 5 faded blue bags, 6 dotted black bags.
            faded blue bags contain no other bags.
            dotted black bags contain no other bags.
            INPUT;

        yield '126' => <<<'INPUT'
            shiny gold bags contain 2 dark red bags.
            dark red bags contain 2 dark orange bags.
            dark orange bags contain 2 dark yellow bags.
            dark yellow bags contain 2 dark green bags.
            dark green bags contain 2 dark blue bags.
            dark blue bags contain 2 dark violet bags.
            dark violet bags contain no other bags.
            INPUT;
    }

    public function solvePart1(string $input): string
    {
        $bags = $this->parseBagRequirements($input);

        $unique = [];
        $queue = new SplQueue();
        $queue->enqueue($bags[self::OUR_BAG]);

        while ($queue->count() > 0) {
            $item = $queue->dequeue();

            foreach ($bags as $bag) {
                if (isset($bag->contains[$item->color])) {
                    $unique[$bag->color] = true;
                    $queue->enqueue($bag);
                }
            }
        }

        return (string) count($unique);
    }

    public function solvePart2(string $input): string
    {
        $bags = $this->parseBagRequirements($input);

        return (string) $this->countBagInventory(self::OUR_BAG, $bags);
    }

    /***
     * @param string $type
     * @param array<string, BagRequirements> $bags
     *
     * @return int
     */
    private function countBagInventory(string $type, array $bags): int
    {
        $sum = 0;

        foreach ($bags[$type]->contains as $color => $number) {
            $sum += $number + $number * $this->countBagInventory($color, $bags);
        }

        return $sum;
    }

    /**
     * @param string $input
     *
     * @return array<string, BagRequirements>
     */
    private function parseBagRequirements(string $input): array
    {
        $input = explode("\n", $input);
        $bags = [];

        foreach ($input as $rules) {
            $bag = new BagRequirements();
            $bag->color = explode(' bags ', $rules)[0];

            if (str_contains($rules, 'no other bags')) {
                $bag->contains = [];
            } else {
                preg_match_all('/(\d+)\s(\w+\s+\w+)\sbag/', $rules, $matches);

                foreach ($matches[1] as $key => $value) {
                    $bag->contains[$matches[2][$key]] = $value;
                }
            }

            $bags[$bag->color] = $bag;
        }

        return $bags;
    }
}
