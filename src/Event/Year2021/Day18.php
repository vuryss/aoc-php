<?php

declare(strict_types=1);

namespace App\Event\Year2021;

use App\Event\DayInterface;
use App\Event\Year2021\Helpers\SnailfishNumber;

class Day18 implements DayInterface
{
    public function testPart1(): iterable
    {
        yield '3488' => <<<'INPUT'
            [[[0,[4,5]],[0,0]],[[[4,5],[2,6]],[9,5]]]
            [7,[[[3,7],[4,3]],[[6,3],[8,8]]]]
            [[2,[[0,8],[3,4]]],[[[6,7],1],[7,[1,6]]]]
            [[[[2,4],7],[6,[0,5]]],[[[6,8],[2,8]],[[2,1],[4,5]]]]
            [7,[5,[[3,8],[1,4]]]]
            [[2,[2,2]],[8,[8,1]]]
            [2,9]
            [1,[[[9,3],9],[[9,0],[0,7]]]]
            [[[5,[7,4]],7],1]
            [[[[4,2],2],6],[8,7]]
            INPUT;

        yield '4140' => <<<'INPUT'
            [[[0,[5,8]],[[1,7],[9,6]]],[[4,[1,2]],[[1,4],2]]]
            [[[5,[2,8]],4],[5,[[9,9],0]]]
            [6,[[[6,2],[5,6]],[[7,6],[4,7]]]]
            [[[6,[0,7]],[0,9]],[4,[9,[9,0]]]]
            [[[7,[6,4]],[3,[1,3]]],[[[5,5],1],9]]
            [[6,[[7,3],[3,2]]],[[[3,8],[5,7]],4]]
            [[[[5,4],[7,7]],8],[[8,3],8]]
            [[9,3],[[9,9],[6,[4,9]]]]
            [[2,[[7,7],7]],[[5,8],[[9,3],[0,2]]]]
            [[[[5,2],5],[8,[3,7]]],[[5,[7,5]],[4,4]]]
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '3993' => <<<'INPUT'
            [[[0,[5,8]],[[1,7],[9,6]]],[[4,[1,2]],[[1,4],2]]]
            [[[5,[2,8]],4],[5,[[9,9],0]]]
            [6,[[[6,2],[5,6]],[[7,6],[4,7]]]]
            [[[6,[0,7]],[0,9]],[4,[9,[9,0]]]]
            [[[7,[6,4]],[3,[1,3]]],[[[5,5],1],9]]
            [[6,[[7,3],[3,2]]],[[[3,8],[5,7]],4]]
            [[[[5,4],[7,7]],8],[[8,3],8]]
            [[9,3],[[9,9],[6,[4,9]]]]
            [[2,[[7,7],7]],[[5,8],[[9,3],[0,2]]]]
            [[[[5,2],5],[8,[3,7]]],[[5,[7,5]],[4,4]]]
            INPUT;
    }

    public function solvePart1(string $input): string|int
    {
        $numbers = explode("\n", $input);
        $number = new SnailfishNumber($numbers[0]);

        for ($i = 1, $numbersCount = count($numbers); $i < $numbersCount; $i++) {
            $number = $number->add(new SnailfishNumber($numbers[$i]));
        }

        return $number->magnitude();
    }

    public function solvePart2(string $input): string|int
    {
        $numbers = explode("\n", $input);
        $max = 0;

        foreach ($numbers as $index => $number) {
            foreach ($numbers as $index2 => $number2) {
                if ($index === $index2) {
                    continue;
                }

                $max = max(
                    $max,
                    (new SnailfishNumber($number))->add(new SnailfishNumber($number2))->magnitude(),
                    (new SnailfishNumber($number2))->add(new SnailfishNumber($number))->magnitude(),
                );
            }
        }

        return $max;
    }
}
