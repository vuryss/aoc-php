<?php

declare(strict_types=1);

namespace App\Event\Year2020;

use App\Event\DayInterface;

class Day24 implements DayInterface
{
    public function testPart1(): iterable
    {
        yield '10' => <<<'INPUT'
            sesenwnenenewseeswwswswwnenewsewsw
            neeenesenwnwwswnenewnwwsewnenwseswesw
            seswneswswsenwwnwse
            nwnwneseeswswnenewneswwnewseswneseene
            swweswneswnenwsewnwneneseenw
            eesenwseswswnenwswnwnwsewwnwsene
            sewnenenenesenwsewnenwwwse
            wenwwweseeeweswwwnwwe
            wsweesenenewnwwnwsenewsenwwsesesenwne
            neeswseenwwswnwswswnw
            nenwswwsewswnenenewsenwsenwnesesenew
            enewnwewneswsewnwswenweswnenwsenwsw
            sweneswneswneneenwnewenewwneswswnese
            swwesenesewenwneswnwwneseswwne
            enesenwswwswneneswsenwnewswseenwsese
            wnwnesenesenenwwnenwsewesewsesesew
            nenewswnwewswnenesenwnesewesw
            eneswnwswnwsenenwnwnwwseeswneewsenese
            neswnwewnwnwseenwseesewsenwsweewe
            wseweeenwnesenwwwswnew
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '2208' => <<<'INPUT'
            sesenwnenenewseeswwswswwnenewsewsw
            neeenesenwnwwswnenewnwwsewnenwseswesw
            seswneswswsenwwnwse
            nwnwneseeswswnenewneswwnewseswneseene
            swweswneswnenwsewnwneneseenw
            eesenwseswswnenwswnwnwsewwnwsene
            sewnenenenesenwsewnenwwwse
            wenwwweseeeweswwwnwwe
            wsweesenenewnwwnwsenewsenwwsesesenwne
            neeswseenwwswnwswswnw
            nenwswwsewswnenenewsenwsenwnesesenew
            enewnwewneswsewnwswenweswnenwsenwsw
            sweneswneswneneenwnewenewwneswswnese
            swwesenesewenwneswnwwneseswwne
            enesenwswwswneneswsenwnewswseenwsese
            wnwnesenesenenwwnenwsewesewsesesew
            nenewswnwewswnenesenwnesewesw
            eneswnwswnwsenenwnwnwwseeswneewsenese
            neswnwewnwnwseenwseesewsenwsweewe
            wseweeenwnesenwwwswnew
            INPUT;
    }

    public function solvePart1(string $input): string
    {
        $lines = explode("\n", $input);

        // Axial coordinates, see https://www.redblobgames.com/grids/hexagons/
        $grid = [];

        foreach ($lines as $line) {
            [$r, $q] = $this->getCoordinates($line);

            $grid[$r][$q] = ($grid[$r][$q] ??= 0) ? 0 : 1;
        }

        return (string) $this->countBlackTiles($grid);
    }

    public function solvePart2(string $input): string
    {
        $lines = explode("\n", $input);

        // Axial coordinates, see https://www.redblobgames.com/grids/hexagons/
        $grid = [];

        $maxR = $minR = $maxQ = $minQ = 0;

        foreach ($lines as $line) {
            [$r, $q] = $this->getCoordinates($line);

            $grid[$r][$q] = ($grid[$r][$q] ??= 0) ? 0 : 1;

            $minR = min($minR, $r);
            $maxR = max($maxR, $r);
            $minQ = min($minQ, $q);
            $maxQ = max($maxQ, $q);
        }

        for ($i = 1; $i <= 100; $i++) {
            $newGrid = $grid;

            for ($r = $minR - 1; $r <= $maxR + 1; $r++) {
                for ($q = $minQ - 1; $q <= $maxQ + 1; $q++) {
                    $adjacentBlacksCount =
                        ($grid[$r - 1][$q] ?? 0)
                        + ($grid[$r - 1][$q + 1] ?? 0)
                        + ($grid[$r][$q - 1] ?? 0)
                        + ($grid[$r][$q + 1] ?? 0)
                        + ($grid[$r + 1][$q - 1] ?? 0)
                        + ($grid[$r + 1][$q] ?? 0);

                    $isBlack = $grid[$r][$q] ?? 0;

                    if ($isBlack === 0 && $adjacentBlacksCount === 2) {
                        $newGrid[$r][$q] = 1;
                        $minR = min($minR, $r);
                        $maxR = max($maxR, $r);
                        $minQ = min($minQ, $q);
                        $maxQ = max($maxQ, $q);
                    }

                    if ($isBlack === 1 && ($adjacentBlacksCount === 0 || $adjacentBlacksCount > 2)) {
                        $newGrid[$r][$q] = 0;
                        $minR = min($minR, $r);
                        $maxR = max($maxR, $r);
                        $minQ = min($minQ, $q);
                        $maxQ = max($maxQ, $q);
                    }
                }
            }

            $grid = $newGrid;
        }

        return (string) $this->countBlackTiles($grid);
    }

    /**
     * @param string $line
     * @return array{int, int}
     */
    private function getCoordinates(string $line): array
    {
        $r = $q = 0;

        while ($line !== '') {
            switch ($line[0]) {
                case 'e':
                    $q++;
                    $line = substr($line, 1);
                    break;
                case 'w':
                    $q--;
                    $line = substr($line, 1);
                    break;
                case 's':
                    $r++;
                    if ($line[1] === 'w') {
                        $q--;
                    }
                    $line = substr($line, 2);
                    break;
                case 'n':
                    $r--;
                    if ($line[1] === 'e') {
                        $q++;
                    }
                    $line = substr($line, 2);
                    break;
            }
        }

        return [$r, $q];
    }

    private function countBlackTiles(array $grid): int
    {
        $blacks = 0;

        foreach ($grid as $grid2) {
            foreach ($grid2 as $value) {
                $blacks += $value;
            }
        }

        return $blacks;
    }
}
