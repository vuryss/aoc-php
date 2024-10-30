<?php

/** @noinspection SensitiveParameterInspection */

declare(strict_types=1);

namespace App\Event\Year2023;

use App\Event\DayInterface;

class Day7 implements DayInterface
{
    private const array TYPE = [
        'fiveOfAKind' => 10,
        'fourOfAKind' => 9,
        'fullHouse' => 8,
        'threeOfAKind' => 7,
        'twoPair' => 6,
        'onePair' => 5,
        'highCard' => 4,
    ];

    public function testPart1(): iterable
    {
        yield '6440' => <<<'INPUT'
            32T3K 765
            T55J5 684
            KK677 28
            KTJJT 220
            QQQJA 483
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '5905' => <<<'INPUT'
            32T3K 765
            T55J5 684
            KK677 28
            KTJJT 220
            QQQJA 483
            INPUT;
    }

    public function solvePart1(string $input): string|int
    {
        $lines = explode("\n", $input);
        $hands = [];

        foreach ($lines as $line) {
            [$hand, $bid] = explode(' ', $line);
            $hands[] = $this->processHand([
                'hand' => $hand,
                'cardCount' => count_chars($hand, 1),
                'bid' => (int) $bid,
                'type' => null,
                'powers' => [],
            ]);
        }

        usort($hands, $this->compareHand(...));

        $total = 0;

        foreach ($hands as $key => $hand) {
            $total += $hand['bid'] * ($key + 1);
        }

        return $total;
    }

    public function solvePart2(string $input): string|int
    {
        $lines = explode("\n", $input);
        $hands = [];

        foreach ($lines as $line) {
            [$hand, $bid] = explode(' ', $line);
            $hands[] = $this->processHand2([
                'hand' => $hand,
                'cardCount' => count_chars($hand, 1),
                'bid' => (int) $bid,
                'type' => null,
                'powers' => [],
            ]);
        }

        usort($hands, $this->compareHand(...));

        $total = 0;

        foreach ($hands as $key => $hand) {
            $total += $hand['bid'] * ($key + 1);
        }

        return $total;
    }

    private function processHand(array $hand): array
    {
        $hand['type'] = $this->getHandType($hand['cardCount']);
        $hand['powers'] = array_map(
            static fn (string $card): int => match ($card) {
                'A' => 14,
                'K' => 13,
                'Q' => 12,
                'J' => 11,
                'T' => 10,
                default => (int) $card,
            },
            str_split($hand['hand'])
        );

        return $hand;
    }

    private function getHandType(array $cardCount): int
    {
        if (1 === count($cardCount)) {
            return self::TYPE['fiveOfAKind'];
        }

        rsort($cardCount);

        if (4 === $cardCount[0]) {
            return self::TYPE['fourOfAKind'];
        }

        if (3 === $cardCount[0] && 2 === $cardCount[1]) {
            return self::TYPE['fullHouse'];
        }

        if (3 === $cardCount[0]) {
            return self::TYPE['threeOfAKind'];
        }

        if (2 === $cardCount[0] && 2 === $cardCount[1]) {
            return self::TYPE['twoPair'];
        }

        if (2 === $cardCount[0]) {
            return self::TYPE['onePair'];
        }

        return self::TYPE['highCard'];
    }

    private function processHand2(array $hand): array
    {
        $maxType = 0;

        foreach ($hand['cardCount'] as $char => $count) {
            $newHand = str_replace('J', chr($char), $hand['hand']);
            $newCardCount = count_chars($newHand, 1);
            $type = $this->getHandType($newCardCount);
            $maxType = max($maxType, $type);
        }

        $hand['type'] = $maxType;
        $hand['powers'] = array_map(
            static fn (string $card): int => match ($card) {
                'A' => 14,
                'K' => 13,
                'Q' => 12,
                'J' => 1,
                'T' => 10,
                default => (int) $card,
            },
            str_split($hand['hand'])
        );

        return $hand;
    }

    private function compareHand(array $hand1, array $hand2): int
    {
        if ($hand1['type'] !== $hand2['type']) {
            return $hand1['type'] <=> $hand2['type'];
        }

        $powers1 = $hand1['powers'];
        $powers2 = $hand2['powers'];

        for ($i = 0; $i < 5; $i++) {
            if ($powers1[$i] !== $powers2[$i]) {
                return $powers1[$i] <=> $powers2[$i];
            }
        }

        return 0;
    }
}
