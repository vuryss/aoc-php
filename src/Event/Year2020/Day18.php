<?php

declare(strict_types=1);

namespace App\Event\Year2020;

use App\Event\DayInterface;

class Day18 implements DayInterface
{
    public function testPart1(): iterable
    {
        yield '71' => '1 + 2 * 3 + 4 * 5 + 6';
        yield '51' => '1 + (2 * 3) + (4 * (5 + 6))';
        yield '26' => '2 * 3 + (4 * 5)';
        yield '437' => '5 + (8 * 3 + 9 + 3 * 4 * 3)';
        yield '12240' => '5 * 9 * (7 * 3 * 3 + 9 * 3 + (8 + 6 * 4))';
        yield '13632' => '((2 + 4 * 9) * (6 + 9 * 8 + 6) + 6) + 2 + 4 * 2';
    }

    public function testPart2(): iterable
    {
        yield '231' => '1 + 2 * 3 + 4 * 5 + 6';
        yield '51' => '1 + (2 * 3) + (4 * (5 + 6))';
        yield '46' => '2 * 3 + (4 * 5)';
        yield '1445' => '5 + (8 * 3 + 9 + 3 * 4 * 3)';
        yield '669060' => '5 * 9 * (7 * 3 * 3 + 9 * 3 + (8 + 6 * 4))';
        yield '23340' => '((2 + 4 * 9) * (6 + 9 * 8 + 6) + 6) + 2 + 4 * 2';
    }

    public function solvePart1(string $input): string
    {
        $equations = explode("\n", $input);
        $sum = 0;

        foreach ($equations as $equation) {
            $sum += $this->solveBasic($equation);
        }

        return (string) $sum;
    }

    public function solvePart2(string $input): string
    {
        $equations = explode("\n", $input);
        $sum = 0;

        foreach ($equations as $equation) {
            $equation = strtr($equation, [' ' => '']);
            $equation = str_split($equation);
            $sum += $this->solveAdvanced($equation);
        }

        return (string) $sum;
    }

    private function solveBasic(string &$equation): int
    {
        while (true) {
            if (!isset($left)) {
                if (ctype_digit($equation[0])) {
                    $left = $equation[0];
                } else {
                    $equation = trim(substr($equation, 1));
                    $left = $this->solveBasic($equation);
                }

                $equation = trim(substr($equation, 1));
            }

            $sign = $equation[0];

            $equation = trim(substr($equation, 1));
            $right = null;

            if (ctype_digit($equation[0])) {
                $right = $equation[0];
            } else {
                $equation = trim(substr($equation, 1));
                $right = $this->solveBasic($equation);
            }

            if ($sign === '+') {
                $left += $right;
            } elseif ($sign === '*') {
                $left *= $right;
            }

            $equation = trim(substr($equation, 1));

            if (empty($equation) || $equation[0] === ')') {
                return $left;
            }
        }
    }

    private function solveAdvanced(array $equation): int
    {
        foreach (['+', '*'] as $op) {
            $left = null;
            $i = 0;

            while (true) {
                if (!isset($equation[$i]) || $equation[$i] === ')') {
                    break;
                }

                if (!isset($left)) {
                    $startOperation = $i;
                    $left = $this->resolveOperand($equation, $i);
                    $i += 1;
                }

                $sign = $equation[$i];
                $i  += 1;

                $right = $this->resolveOperand($equation, $i);

                if ($sign !== $op) {
                    $left = $right;
                    $startOperation = $i++;
                    continue;
                }

                if ($sign === '+') {
                    $left += $right;
                } elseif ($sign === '*') {
                    $left *= $right;
                }

                $equation[$startOperation] = $left;

                array_splice($equation, $startOperation + 1, $i - $startOperation);

                $i = $startOperation + 1;
            }

            if (count($equation) === 1) break;
        }

        return $equation[0];
    }

    private function resolveOperand(array &$equation, int &$i): int
    {
        $startPosition = $i;

        if (is_numeric($equation[$i])) {
            return (int) $equation[$i];
        }

        $operand = $this->solveAdvanced($this->getSubEquation($equation, $i));
        array_splice($equation, $startPosition, $i - $startPosition, [$operand]);
        $i = $startPosition;

        return $operand;
    }

    private function getSubEquation(array $equation, int &$checkPosition): array
    {
        $depth = 1;
        $checkPosition++;
        $subEquationStart = $checkPosition;

        while ($depth > 0) {
            $char = $equation[$checkPosition++];

            if ($char === '(') {
                $depth++;
            } elseif ($char === ')') {
                $depth--;
            }
        }

        return array_slice($equation, $subEquationStart, $checkPosition - $subEquationStart - 1);
    }
}
