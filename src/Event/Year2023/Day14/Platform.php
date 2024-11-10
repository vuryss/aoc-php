<?php

declare(strict_types=1);

namespace App\Event\Year2023\Day14;

class Platform
{
    private array $grid;
    private int $maxX;
    private int $maxY;

    public function __construct(
        string $input,
    ) {
        $lines = explode("\n", $input);
        $this->maxX = strlen($lines[0]) - 1;
        $this->maxY = count($lines) - 1;
        $this->grid = [];

        foreach ($lines as $y => $line) {
            foreach (str_split($line) as $x => $item) {
                $this->grid[$y][$x] = $item;
            }
        }
    }

    public function tiltNorth(): void
    {
        for ($x = 0; $x <= $this->maxX; $x++) {
            $freeSpacePointer = 0;

            while (true) {
                while (isset($this->grid[$freeSpacePointer][$x]) && '.' !== $this->grid[$freeSpacePointer][$x]) {
                    $freeSpacePointer++;
                }

                if ($this->maxY < $freeSpacePointer) {
                    break;
                }

                $rockPointer = $freeSpacePointer + 1;

                while (isset($this->grid[$rockPointer][$x]) && 'O' !== $this->grid[$rockPointer][$x] && '#' !== $this->grid[$rockPointer][$x]) {
                    $rockPointer++;
                }

                if ($this->maxY < $rockPointer) {
                    break;
                }

                if ('#' === $this->grid[$rockPointer][$x]) {
                    $freeSpacePointer = $rockPointer + 1;
                    continue;
                }

                $this->grid[$freeSpacePointer][$x] = 'O';
                $this->grid[$rockPointer][$x] = '.';
            }
        }
    }

    public function tiltWest(): void
    {
        for ($y = 0; $y <= $this->maxY; $y++) {
            $freeSpacePointer = 0;

            while (true) {
                while (isset($this->grid[$y][$freeSpacePointer]) && '.' !== $this->grid[$y][$freeSpacePointer]) {
                    $freeSpacePointer++;
                }

                if ($this->maxX < $freeSpacePointer) {
                    break;
                }

                $rockPointer = $freeSpacePointer + 1;

                while (isset($this->grid[$y][$rockPointer]) && 'O' !== $this->grid[$y][$rockPointer] && '#' !== $this->grid[$y][$rockPointer]) {
                    $rockPointer++;
                }

                if ($this->maxX < $rockPointer) {
                    break;
                }

                if ('#' === $this->grid[$y][$rockPointer]) {
                    $freeSpacePointer = $rockPointer + 1;
                    continue;
                }

                $this->grid[$y][$freeSpacePointer] = 'O';
                $this->grid[$y][$rockPointer] = '.';
            }
        }
    }

    public function tiltSouth(): void
    {
        for ($x = 0; $x <= $this->maxX; $x++) {
            $freeSpacePointer = $this->maxY;

            while (true) {
                while (isset($this->grid[$freeSpacePointer][$x]) && '.' !== $this->grid[$freeSpacePointer][$x]) {
                    $freeSpacePointer--;
                }

                if (0 > $freeSpacePointer) {
                    break;
                }

                $rockPointer = $freeSpacePointer - 1;

                while (isset($this->grid[$rockPointer][$x]) && 'O' !== $this->grid[$rockPointer][$x] && '#' !== $this->grid[$rockPointer][$x]) {
                    $rockPointer--;
                }

                if (0 > $rockPointer) {
                    break;
                }

                if ('#' === $this->grid[$rockPointer][$x]) {
                    $freeSpacePointer = $rockPointer - 1;
                    continue;
                }

                $this->grid[$freeSpacePointer][$x] = 'O';
                $this->grid[$rockPointer][$x] = '.';
            }
        }
    }

    public function tiltEast(): void
    {
        for ($y = 0; $y <= $this->maxY; $y++) {
            $freeSpacePointer = $this->maxX;

            while (true) {
                while (isset($this->grid[$y][$freeSpacePointer]) && '.' !== $this->grid[$y][$freeSpacePointer]) {
                    $freeSpacePointer--;
                }

                if (0 > $freeSpacePointer) {
                    break;
                }

                $rockPointer = $freeSpacePointer - 1;

                while (isset($this->grid[$y][$rockPointer]) && 'O' !== $this->grid[$y][$rockPointer] && '#' !== $this->grid[$y][$rockPointer]) {
                    $rockPointer--;
                }

                if (0 > $rockPointer) {
                    break;
                }

                if ('#' === $this->grid[$y][$rockPointer]) {
                    $freeSpacePointer = $rockPointer - 1;
                    continue;
                }

                $this->grid[$y][$freeSpacePointer] = 'O';
                $this->grid[$y][$rockPointer] = '.';
            }
        }
    }

    public function hash(): string
    {
        return json_encode($this->grid);
    }

    public function score(): int
    {
        $score = 0;

        for ($y = 0; $y <= $this->maxY; $y++) {
            $weight = $this->maxY - $y + 1;

            for ($x = 0; $x <= $this->maxX; $x++) {
                if ('O' === $this->grid[$y][$x]) {
                    $score += $weight;
                }
            }
        }

        return $score;
    }
}
