<?php

declare(strict_types=1);

namespace App\Event\Year2021;

use App\Event\DayInterface;
use App\Event\Year2021\Helpers\Cave;
use Ds\Queue;

class Day12 implements DayInterface
{
    public function testPart1(): iterable
    {
        yield '10' => <<<'INPUT'
            start-A
            start-b
            A-c
            A-b
            b-d
            A-end
            b-end
            INPUT;

        yield '19' => <<<'INPUT'
            dc-end
            HN-start
            start-kj
            dc-start
            dc-HN
            LN-dc
            HN-end
            kj-sa
            kj-HN
            kj-dc
            INPUT;

        yield '226' => <<<'INPUT'
            fs-end
            he-DX
            fs-he
            start-DX
            pj-DX
            end-zg
            zg-sl
            zg-pj
            pj-he
            RW-he
            fs-DX
            pj-RW
            zg-RW
            start-pj
            he-WI
            zg-he
            pj-fs
            start-RW
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '36' => <<<'INPUT'
            start-A
            start-b
            A-c
            A-b
            b-d
            A-end
            b-end
            INPUT;

        yield '103' => <<<'INPUT'
            dc-end
            HN-start
            start-kj
            dc-start
            dc-HN
            LN-dc
            HN-end
            kj-sa
            kj-HN
            kj-dc
            INPUT;

        yield '3509' => <<<'INPUT'
            fs-end
            he-DX
            fs-he
            start-DX
            pj-DX
            end-zg
            zg-sl
            zg-pj
            pj-he
            RW-he
            fs-DX
            pj-RW
            zg-RW
            start-pj
            he-WI
            zg-he
            pj-fs
            start-RW
            INPUT;
    }

    public function solvePart1(string $input): string
    {
        $caves = $this->parseCaves($input);
        $queue = new Queue([[$caves['start'], ['start' => true]]]);
        $endPaths = [];

        while (!$queue->isEmpty()) {
            /** @var Cave $current */
            [$current, $path] = $queue->pop();

            if ($current->isEnd) {
                $endPaths[] = $path;
                continue;
            }

            foreach ($current->adjacent as $caveName) {
                if ($caves[$caveName]->isLower && isset($path[$caveName])) {
                    continue;
                }

                $queue->push([$caves[$caveName], $path + [$caveName => true]]);
            }
        }

        return (string) count($endPaths);
    }

    public function solvePart2(string $input): string
    {
        $caves = $this->parseCaves($input);

        $queue = new Queue([[$caves['start'], ['start' => true], false]]);
        $endPaths = 0;

        while (!$queue->isEmpty()) {
            /** @var Cave $current */
            [$current, $path, $twiceFlag] = $queue->pop();

            if ($current->isEnd) {
                $endPaths++;
                continue;
            }

            foreach ($current->adjacent as $caveName) {
                $currentTwiceFlag = $twiceFlag;

                if ($caves[$caveName]->isLower && isset($path[$caveName])) {
                    if ($caves[$caveName]->isStart || $currentTwiceFlag) {
                        continue;
                    }

                    $currentTwiceFlag = true;
                }

                $queue->push([$caves[$caveName], $path + [$caveName => true], $currentTwiceFlag]);
            }
        }

        return (string) $endPaths;
    }

    /**
     * @return Cave[]
     */
    private function parseCaves(string $input): array
    {
        $lines = explode("\n", $input);
        $caves = [];

        foreach ($lines as $line) {
            [$cave1, $cave2] = explode('-', $line);

            $caves[$cave1] ??= new Cave($cave1);
            $caves[$cave1]->adjacent[] = $cave2;

            $caves[$cave2] ??= new Cave($cave2);
            $caves[$cave2]->adjacent[] = $cave1;
        }

        return $caves;
    }
}
