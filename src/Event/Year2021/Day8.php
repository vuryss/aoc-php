<?php

declare(strict_types=1);

namespace App\Event\Year2021;

use App\Event\DayInterface;

class Day8 implements DayInterface
{
    public function testPart1(): iterable
    {
        yield '0' => <<<'INPUT'
            acedgfb cdfbe gcdfa fbcad dab cefabd cdfgeb eafb cagedb ab | cdfeb fcadb cdfeb cdbaf
            INPUT;

        yield '26' => <<<'INPUT'
            be cfbegad cbdgef fgaecd cgeb fdcge agebfd fecdb fabcd edb | fdgacbe cefdb cefbgd gcbe
            edbfga begcd cbg gc gcadebf fbgde acbgfd abcde gfcbed gfec | fcgedb cgb dgebacf gc
            fgaebd cg bdaec gdafb agbcfd gdcbef bgcad gfac gcb cdgabef | cg cg fdcagb cbg
            fbegcd cbd adcefb dageb afcb bc aefdc ecdab fgdeca fcdbega | efabcd cedba gadfec cb
            aecbfdg fbg gf bafeg dbefa fcge gcbea fcaegb dgceab fcbdga | gecf egdcabf bgf bfgea
            fgeab ca afcebg bdacfeg cfaedg gcfdb baec bfadeg bafgc acf | gebdcfa ecba ca fadegcb
            dbcfg fgd bdegcaf fgec aegbdf ecdfab fbedc dacgb gdcebf gf | cefg dcbef fcge gbcadfe
            bdfegc cbegaf gecbf dfcage bdacg ed bedf ced adcbefg gebcd | ed bcgafe cdgba cbgef
            egadfb cdbfeg cegd fecab cgb gbdefca cg fgcdab egfdb bfceg | gbdfcae bgc cg cgb
            gcafb gcf dcaebfg ecagb gf abcdeg gaef cafbge fdbac fegbdc | fgae cfgab fg bagce
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '5353' => <<<'INPUT'
            acedgfb cdfbe gcdfa fbcad dab cefabd cdfgeb eafb cagedb ab | cdfeb fcadb cdfeb cdbaf
            INPUT;

        yield '61229' => <<<'INPUT'
            be cfbegad cbdgef fgaecd cgeb fdcge agebfd fecdb fabcd edb | fdgacbe cefdb cefbgd gcbe
            edbfga begcd cbg gc gcadebf fbgde acbgfd abcde gfcbed gfec | fcgedb cgb dgebacf gc
            fgaebd cg bdaec gdafb agbcfd gdcbef bgcad gfac gcb cdgabef | cg cg fdcagb cbg
            fbegcd cbd adcefb dageb afcb bc aefdc ecdab fgdeca fcdbega | efabcd cedba gadfec cb
            aecbfdg fbg gf bafeg dbefa fcge gcbea fcaegb dgceab fcbdga | gecf egdcabf bgf bfgea
            fgeab ca afcebg bdacfeg cfaedg gcfdb baec bfadeg bafgc acf | gebdcfa ecba ca fadegcb
            dbcfg fgd bdegcaf fgec aegbdf ecdfab fbedc dacgb gdcebf gf | cefg dcbef fcge gbcadfe
            bdfegc cbegaf gecbf dfcage bdacg ed bedf ced adcbefg gebcd | ed bcgafe cdgba cbgef
            egadfb cdbfeg cegd fecab cgb gbdefca cg fgcdab egfdb bfceg | gbdfcae bgc cg cgb
            gcafb gcf dcaebfg ecagb gf abcdeg gaef cafbge fdbac fegbdc | fgae cfgab fg bagce
            INPUT;
    }

    public function solvePart1(string $input): string
    {
        $lines = explode("\n", $input);
        $count = 0;

        foreach ($lines as $line) {
            $line = explode(' | ', $line);
            $numbers = explode(' ', $line[1]);
            foreach ($numbers as $number) {
                if (in_array(strlen($number), [2, 4, 3, 7])) {
                    $count++;
                }
            }
        }

        return (string) $count;
    }

    public function solvePart2(string $input): string
    {
        $lines = explode("\n", $input);
        $sum = 0;

        foreach ($lines as $line) {
            $sum += $this->solveLine($line);
        }

        return (string) $sum;
    }

    private function solveLine(string $line): int
    {
        $lineSegments = explode(' | ', $line);
        $numbers = explode(' ', $lineSegments[0]);
        $numbersBySegmentCount = [];
        $resolvedNumbers = [];

        foreach ($numbers as $number) {
            $segmentCount = strlen($number);
            $numbersBySegmentCount[$segmentCount][] = str_split($number);
        }

        $resolvedNumbers[1] = $numbersBySegmentCount[2][0];
        $resolvedNumbers[4] = $numbersBySegmentCount[4][0];
        $resolvedNumbers[7] = $numbersBySegmentCount[3][0];
        $resolvedNumbers[8] = $numbersBySegmentCount[7][0];

        foreach ($numbersBySegmentCount[6] as $key => $sixSegmentsNumber) {
            if (count(array_intersect($resolvedNumbers[4], $sixSegmentsNumber)) === 4) {
                $resolvedNumbers[9] = $numbersBySegmentCount[6][$key];
                unset($numbersBySegmentCount[6][$key]);
                break;
            }
        }

        foreach ($numbersBySegmentCount[6] as $key => $sixSegmentsNumber) {
            if (count(array_intersect($resolvedNumbers[7], $sixSegmentsNumber)) === 3) {
                $resolvedNumbers[0] = $numbersBySegmentCount[6][$key];
                unset($numbersBySegmentCount[6][$key]);
                break;
            }
        }

        $resolvedNumbers[6] = current($numbersBySegmentCount[6]);

        foreach ($numbersBySegmentCount[5] as $key => $fiveSegmentNumber) {
            if (count(array_intersect($resolvedNumbers[7], $fiveSegmentNumber)) === 3) {
                $resolvedNumbers[3] = $numbersBySegmentCount[5][$key];
                unset($numbersBySegmentCount[5][$key]);
                break;
            }
        }

        foreach ($numbersBySegmentCount[5] as $key => $fiveSegmentNumber) {
            if (count(array_intersect($resolvedNumbers[6], $fiveSegmentNumber)) === 5) {
                $resolvedNumbers[5] = $numbersBySegmentCount[5][$key];
                unset($numbersBySegmentCount[5][$key]);
                break;
            }
        }

        $resolvedNumbers[2] = current($numbersBySegmentCount[5]);

        foreach ($resolvedNumbers as $key => $number) {
            sort($number);
            $resolvedNumbers[$key] = $number;
        }

        $numbers = explode(' ', $lineSegments[1]);

        $code = '';

        foreach ($numbers as $number) {
            $number = str_split($number);
            sort($number);
            foreach ($resolvedNumbers as $num => $resolvedNumber) {
                if ($resolvedNumber === $number) {
                    $code .= $num;
                }
            }
        }

        return (int) $code;
    }
}
