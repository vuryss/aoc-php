<?php

declare(strict_types=1);

namespace App\Event\Year2024;

use App\Event\DayInterface;
use App\Util\Algorithms;

class Day23 implements DayInterface
{
    public function testPart1(): iterable
    {
        yield '7' => <<<'INPUT'
            kh-tc
            qp-kh
            de-cg
            ka-co
            yn-aq
            qp-ub
            cg-tb
            vc-aq
            tb-ka
            wh-tc
            yn-cg
            kh-ub
            ta-co
            de-co
            tc-td
            tb-wq
            wh-td
            ta-ka
            td-qp
            aq-cg
            wq-ub
            ub-vc
            de-ta
            wq-aq
            wq-vc
            wh-yn
            ka-de
            kh-ta
            co-tc
            wh-qp
            tb-vc
            td-yn
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield 'co,de,ka,ta' => <<<'INPUT'
            kh-tc
            qp-kh
            de-cg
            ka-co
            yn-aq
            qp-ub
            cg-tb
            vc-aq
            tb-ka
            wh-tc
            yn-cg
            kh-ub
            ta-co
            de-co
            tc-td
            tb-wq
            wh-td
            ta-ka
            td-qp
            aq-cg
            wq-ub
            ub-vc
            de-ta
            wq-aq
            wq-vc
            wh-yn
            ka-de
            kh-ta
            co-tc
            wh-qp
            tb-vc
            td-yn
            INPUT;
    }

    public function solvePart1(string $input): string|int
    {
        $lines = explode("\n", $input);
        $connections = [];

        foreach ($lines as $line) {
            [$a, $b] = explode('-', $line);
            $connections[$a][] = $b;
            $connections[$b][] = $a;
        }

        $allConnected = [];

        foreach ($connections as $computer => $connected) {
            if ($computer[0] !== 't') {
                continue;
            }

            foreach ($connected as $c1) {
                foreach ($connections[$c1] as $c2) {
                    if (in_array($c2, $connected, true)) {
                        $conn = [$computer, $c1, $c2];
                        sort($conn);
                        $allConnected[implode(',', $conn)] = true;
                    }
                }
            }
        }

        return count($allConnected);
    }

    public function solvePart2(string $input): string|int
    {
        $lines = explode("\n", $input);
        $connections = [];

        foreach ($lines as $line) {
            [$a, $b] = explode('-', $line);
            $connections[$a][] = $b;
            $connections[$b][] = $a;
        }

        $maxClique = Algorithms::maxCliqueBronKerbosch($connections);
        sort($maxClique);

        return implode(',', $maxClique);
    }
}
