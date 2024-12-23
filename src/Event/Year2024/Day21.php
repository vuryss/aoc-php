<?php

declare(strict_types=1);

namespace App\Event\Year2024;

use App\Event\DayInterface;
use App\Util\Point2D;
use App\Util\StringUtil;
use Ds\Queue;

class Day21 implements DayInterface
{
    private const array NUMERIC_PAD = [['7', '8', '9'], ['4', '5', '6'], ['1', '2', '3'], [null, '0', 'A']];
    private const array DIRECTIONAL_PAD = [[null, '^', 'A'], ['<', 'v', '>']];

    private array $numericPadPaths = [];
    private array $directionalPadPaths = [];
    private array $cache = [];

    public function testPart1(): iterable
    {
        yield '126384' => <<<'INPUT'
            029A
            980A
            179A
            456A
            379A
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '154115708116294' => <<<'INPUT'
            029A
            980A
            179A
            456A
            379A
            INPUT;
    }

    public function solvePart1(string $input): string|int
    {
        $this->numericPadPaths = $this->calculateKeyPadPaths(self::NUMERIC_PAD);
        $this->directionalPadPaths = $this->calculateKeyPadPaths(self::DIRECTIONAL_PAD);
        $sum = 0;

        foreach (explode("\n", $input) as $code) {
            $sum += $this->getMinMovements($code, 2) * StringUtil::extractIntegers($code)[0];
        }

        return $sum;
    }

    public function solvePart2(string $input): string|int
    {
        $this->numericPadPaths = $this->calculateKeyPadPaths(self::NUMERIC_PAD);
        $this->directionalPadPaths = $this->calculateKeyPadPaths(self::DIRECTIONAL_PAD);
        $sum = 0;

        foreach (explode("\n", $input) as $code) {
            $sum += $this->getMinMovements($code, 25) * StringUtil::extractIntegers($code)[0];
        }

        return $sum;
    }

    private function getMinMovements(string $characters, int $numberOfRobots = 2): int
    {
        $previous = 'A';
        $count = 0;

        foreach (str_split($characters) as $character) {
            $paths = $this->numericPadPaths[$previous][$character];
            $movements = [];

            foreach ($paths as $path) {
                $movements[] = $this->getMinDirectionalPadMovements($path, $numberOfRobots);
            }

            $previous = $character;
            $count += min($movements);
        }

        return $count;
    }

    private function getMinDirectionalPadMovements(string $characters, int $numberOfRobots = 2): int
    {
        if (isset($this->cache[$characters][$numberOfRobots])) {
            return $this->cache[$characters][$numberOfRobots];
        }

        if ($numberOfRobots === 0) {
            return $this->cache[$characters][$numberOfRobots] = strlen($characters);
        }

        $previous = 'A';
        $count = 0;

        foreach (str_split($characters) as $character) {
            $paths = $this->directionalPadPaths[$previous][$character];
            $movements = [];

            foreach ($paths as $path) {
                $movements[] = $this->getMinDirectionalPadMovements($path, $numberOfRobots - 1);
            }

            $previous = $character;
            $count += min($movements);
        }

        return $this->cache[$characters][$numberOfRobots] = $count;
    }

    /**
     * @param array<int, array<int, string|null>> $keyPad
     * @return array<string, array<string, array<string>>>
     */
    private function calculateKeyPadPaths(array $keyPad): array
    {
        $paths = [];
        $chars = [];

        foreach ($keyPad as $line) {
            foreach ($line as $char) {
                if ($char !== null) {
                    $chars[] = $char;
                }
            }
        }

        foreach ($keyPad as $y => $line) {
            foreach ($line as $x => $char) {
                foreach ($chars as $target) {
                    if ($char === $target) {
                        $paths[$char][$target][] = 'A';
                        continue;
                    }

                    if ($char === null) {
                        continue;
                    }

                    $queue = new Queue();
                    $queue->push([new Point2D($x, $y), '', []]);
                    $minLength = null;

                    while (!$queue->isEmpty()) {
                        /** @var Point2D $position */
                        [$position, $movements, $visited] = $queue->pop();
                        $visited[$position->y][$position->x] = true;

                        if (null !== $minLength && strlen($movements) > $minLength) {
                            break;
                        }

                        if ($keyPad[$position->y][$position->x] === $target) {
                            $minLength ??= strlen($movements);
                            $paths[$char][$target][] = $movements . 'A';
                            continue;
                        }

                        $adjacent = [
                            '>' => $position->east(),
                            '<' => $position->west(),
                            '^' => $position->north(),
                            'v' => $position->south(),
                        ];

                        foreach ($adjacent as $move => $next) {
                            if (isset($keyPad[$next->y][$next->x]) && !isset($visited[$next->y][$next->x])) {
                                $queue->push([$next, $movements . $move, $visited]);
                            }
                        }
                    }
                }
            }
        }

        return $paths;
    }
}
