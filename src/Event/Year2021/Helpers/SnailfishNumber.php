<?php

declare(strict_types=1);

namespace App\Event\Year2021\Helpers;

class SnailfishNumber
{
    private SnailfishNumber|int $leftSide;
    private SnailfishNumber|int $rightSide;

    public function __construct(
        string $number,
        public int $level = 0
    ) {
        $this->parse($number);

        if ($this->level === 0) {
            $this->reduce();
        }
    }

    private function parse(string $number): void
    {
        $number = substr($number, 1, -1);
        $depth = 0;

        for ($i = 0, $len = strlen($number); $i < $len; $i++) {
            if ($number[$i] === '[') {
                $depth++;
            } elseif ($number[$i] === ']') {
                $depth--;
            } elseif ($number[$i] === ',' && $depth === 0) {
                break;
            }
        }

        $left = substr($number, 0, $i);
        $right = substr($number, $i + 1);

        $this->leftSide = ctype_digit($left) ? (int) $left : new SnailfishNumber($left, $this->level + 1);
        $this->rightSide = ctype_digit($right) ? (int) $right : new SnailfishNumber($right, $this->level + 1);
    }

    public function add(SnailfishNumber $number): SnailfishNumber
    {
        return new SnailfishNumber('[' . $this->toString() . ',' . $number->toString() . ']');
    }

    public function toString(): string
    {
        return sprintf(
            '[%s,%s]',
            $this->leftSide instanceof self ? $this->leftSide->toString() : $this->leftSide,
            $this->rightSide instanceof self ? $this->rightSide->toString() : $this->rightSide,
        );
    }

    public function magnitude(): int
    {
        $left = $this->leftSide instanceof self ? $this->leftSide->magnitude() : $this->leftSide;
        $right = $this->rightSide instanceof self ? $this->rightSide->magnitude() : $this->rightSide;

        return 3 * $left + 2 * $right;
    }

    private function reduce(): void
    {
        if ($this->explode() || $this->split()) {
            $this->reduce();
        }
    }

    private function explode(?int &$carryLeft = null, ?int &$carryRight = null): bool
    {
        // Set right value to leftmost node
        if ($carryRight !== null) {
            if ($this->leftSide instanceof self) {
                $this->leftSide->explode($carryLeft, $carryRight);
            } else {
                $this->leftSide += $carryRight;
                $carryRight = null;
            }

            return true;
        }

        // Set left value to the rightmost node
        if ($carryLeft !== null) {
            if ($this->rightSide instanceof self) {
                $this->rightSide->explode($carryLeft, $carryRight);
            } else {
                $this->rightSide += $carryLeft;
                $carryLeft = null;
            }

            return true;
        }

        if ($this->leftSide instanceof self) {
            if ($this->leftSide->level === 4) {
                $carryLeft = (int) $this->leftSide->leftSide;
                $carryRight = (int) $this->leftSide->rightSide;
                $this->leftSide = 0;

                if ($this->rightSide instanceof self) {
                    // Pass only right value to the right side, to be set on the leftmost node there
                    $dummyLeft = null;
                    $this->rightSide->explode($dummyLeft, $carryRight);
                } else {
                    // Set right value to the right node only if the current left node is the one that exploded.
                    $this->rightSide += $carryRight;
                    $carryRight = null;
                }

                return true;
            }

            if ($this->leftSide->explode($carryLeft, $carryRight)) {
                if ($carryRight !== null) {
                    if (is_int($this->rightSide)) {
                        $this->rightSide += $carryRight;
                        $carryRight = null;
                    } else {
                        $dummyLeft = null;
                        $this->rightSide->explode($dummyLeft, $carryRight);
                    }
                }

                return true;
            }
        }

        if ($this->rightSide instanceof self) {
            if ($this->rightSide->level === 4) {
                $carryLeft = (int) $this->rightSide->leftSide;
                $carryRight = (int) $this->rightSide->rightSide;
                $this->rightSide = 0;

                if (is_int($this->leftSide)) {
                    $this->leftSide += $carryLeft;
                    $carryLeft = null;
                } else {
                    $dummyRight = null;
                    $this->leftSide->explode($carryLeft, $dummyRight);
                }

                return true;
            }

            if ($this->rightSide->explode($carryLeft, $carryRight)) {
                if ($carryLeft !== null) {
                    if (is_int($this->leftSide)) {
                        $this->leftSide += $carryLeft;
                        $carryLeft = null;
                    } else {
                        $this->leftSide->explode($carryLeft, $carryRight);
                    }
                }
                return true;
            }
        }

        return false;
    }

    private function split(): bool
    {
        if ($this->leftSide instanceof self) {
            if ($this->leftSide->split()) {
                return true;
            }
        } elseif ($this->leftSide > 9) {
            $this->leftSide = new SnailfishNumber(
                sprintf('[%d,%d]', floor($this->leftSide / 2), ceil($this->leftSide / 2)),
                $this->level + 1
            );
            return true;
        }

        if ($this->rightSide instanceof self) {
            if ($this->rightSide->split()) {
                return true;
            }
        } elseif ($this->rightSide > 9) {
            $this->rightSide = new SnailfishNumber(
                sprintf('[%d,%d]', floor($this->rightSide / 2), ceil($this->rightSide / 2)),
                $this->level + 1
            );
            return true;
        }

        return false;
    }
}
