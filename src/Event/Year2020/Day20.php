<?php

declare(strict_types=1);

namespace App\Event\Year2020;

use App\Event\DayInterface;
use App\Event\Year2020\Helper\Image;
use App\Event\Year2020\Helper\ImageTile;

class Day20 implements DayInterface
{
    public const PATTERN = <<<'INPUT'
                      # 
    #    ##    ##    ###
     #  #  #  #  #  #   
    INPUT;

    public function testPart1(): iterable
    {
        yield '20899048083289' => <<<'INPUT'
            Tile 2311:
            ..##.#..#.
            ##..#.....
            #...##..#.
            ####.#...#
            ##.##.###.
            ##...#.###
            .#.#.#..##
            ..#....#..
            ###...#.#.
            ..###..###
            
            Tile 1951:
            #.##...##.
            #.####...#
            .....#..##
            #...######
            .##.#....#
            .###.#####
            ###.##.##.
            .###....#.
            ..#.#..#.#
            #...##.#..
            
            Tile 1171:
            ####...##.
            #..##.#..#
            ##.#..#.#.
            .###.####.
            ..###.####
            .##....##.
            .#...####.
            #.##.####.
            ####..#...
            .....##...
            
            Tile 1427:
            ###.##.#..
            .#..#.##..
            .#.##.#..#
            #.#.#.##.#
            ....#...##
            ...##..##.
            ...#.#####
            .#.####.#.
            ..#..###.#
            ..##.#..#.
            
            Tile 1489:
            ##.#.#....
            ..##...#..
            .##..##...
            ..#...#...
            #####...#.
            #..#.#.#.#
            ...#.#.#..
            ##.#...##.
            ..##.##.##
            ###.##.#..
            
            Tile 2473:
            #....####.
            #..#.##...
            #.##..#...
            ######.#.#
            .#...#.#.#
            .#########
            .###.#..#.
            ########.#
            ##...##.#.
            ..###.#.#.
            
            Tile 2971:
            ..#.#....#
            #...###...
            #.#.###...
            ##.##..#..
            .#####..##
            .#..####.#
            #..#.#..#.
            ..####.###
            ..#.#.###.
            ...#.#.#.#
            
            Tile 2729:
            ...#.#.#.#
            ####.#....
            ..#.#.....
            ....#..#.#
            .##..##.#.
            .#.####...
            ####.#.#..
            ##.####...
            ##..#.##..
            #.##...##.
            
            Tile 3079:
            #.#.#####.
            .#..######
            ..#.......
            ######....
            ####.#..#.
            .#...#.##.
            #.#####.##
            ..#.###...
            ..#.......
            ..#.###...
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '273' => <<<'INPUT'
            Tile 2311:
            ..##.#..#.
            ##..#.....
            #...##..#.
            ####.#...#
            ##.##.###.
            ##...#.###
            .#.#.#..##
            ..#....#..
            ###...#.#.
            ..###..###
            
            Tile 1951:
            #.##...##.
            #.####...#
            .....#..##
            #...######
            .##.#....#
            .###.#####
            ###.##.##.
            .###....#.
            ..#.#..#.#
            #...##.#..
            
            Tile 1171:
            ####...##.
            #..##.#..#
            ##.#..#.#.
            .###.####.
            ..###.####
            .##....##.
            .#...####.
            #.##.####.
            ####..#...
            .....##...
            
            Tile 1427:
            ###.##.#..
            .#..#.##..
            .#.##.#..#
            #.#.#.##.#
            ....#...##
            ...##..##.
            ...#.#####
            .#.####.#.
            ..#..###.#
            ..##.#..#.
            
            Tile 1489:
            ##.#.#....
            ..##...#..
            .##..##...
            ..#...#...
            #####...#.
            #..#.#.#.#
            ...#.#.#..
            ##.#...##.
            ..##.##.##
            ###.##.#..
            
            Tile 2473:
            #....####.
            #..#.##...
            #.##..#...
            ######.#.#
            .#...#.#.#
            .#########
            .###.#..#.
            ########.#
            ##...##.#.
            ..###.#.#.
            
            Tile 2971:
            ..#.#....#
            #...###...
            #.#.###...
            ##.##..#..
            .#####..##
            .#..####.#
            #..#.#..#.
            ..####.###
            ..#.#.###.
            ...#.#.#.#
            
            Tile 2729:
            ...#.#.#.#
            ####.#....
            ..#.#.....
            ....#..#.#
            .##..##.#.
            .#.####...
            ####.#.#..
            ##.####...
            ##..#.##..
            #.##...##.
            
            Tile 3079:
            #.#.#####.
            .#..######
            ..#.......
            ######....
            ####.#..#.
            .#...#.##.
            #.#####.##
            ..#.###...
            ..#.......
            ..#.###...
            INPUT;
    }

    public function solvePart1(string $input): string
    {
        $tiles = $this->processTiles($input);

        $product = 1;

        foreach ($tiles as $tile) {
            if (count($tile->links) === 2) {
                $product *= $tile->id;
            }
        }

        return (string) $product;
    }

    public function solvePart2(string $input): string
    {
        $tiles = $this->processTiles($input);

        $topLeftTile = null;

        foreach ($tiles as $tile) {
            if (count($tile->links) === 2 && isset($tile->links['R'], $tile->links['B'])) {
                $topLeftTile = $tile;
                break;
            }
        }

        $image = $this->buildImage($topLeftTile);

        $pattern = array_map('str_split', explode("\n", self::PATTERN));
        $deltaMap = [];
        $reqCharacters = count($pattern[0]);
        $reqRows = count($pattern) - 1;

        foreach ($pattern as $rowDelta => $line) {
            foreach ($line as $colDelta => $char) {
                if ($char === '#') {
                    $deltaMap[] = [$rowDelta, $colDelta];
                }
            }
        }

        $foundPatterns = 0;
        $flips = 0;

        while ($flips < 2) {
            for ($i = 0; $i < 4; $i++) {
                foreach ($image->image as $rowIndex => $line) {
                    if (!isset($image->image[$rowIndex + $reqRows])) {
                        break;
                    }

                    foreach ($line as $colIndex => $char) {
                        if (!isset($line[$colIndex + $reqCharacters])) {
                            break;
                        }

                        foreach ($deltaMap as $delta) {
                            if ($image->image[$rowIndex + $delta[0]][$colIndex + $delta[1]] !== '#') {
                                continue 2;
                            }
                        }

                        $foundPatterns++;
                    }
                }

                if ($foundPatterns > 0) {
                    break 2;
                }

                $image->rotateRight();
            }

            $image->horizontalFlip();
            $flips++;
        }

        $totalMatch = 0;
        $patternMatch = count($deltaMap);

        foreach ($image->image as $line) {
            foreach ($line as $char) {
                if ($char === '#') {
                    $totalMatch++;
                }
            }
        }

        return (string) ($totalMatch - ($foundPatterns * $patternMatch));
    }

    /**
     * @return array<ImageTile>
     */
    private function processTiles(string $input): array
    {
        $tilesInput = explode("\n\n", $input);
        $tiles = [];

        foreach ($tilesInput as $tileInput) {
            $lines = explode("\n", $tileInput);
            preg_match('/\d+/', $lines[0], $match);

            $tiles[(int) $match[0]] = new ImageTile(
                id: (int) $match[0],
                image: array_map('str_split', array_slice($lines, 1))
            );
        }

        $firstTile = $tiles[array_key_first($tiles)];
        $unprocessedTiles = $tiles;

        /** @var array<ImageTile> $tilesToProcess */
        $tilesToProcess = [$firstTile->id => $firstTile];
        $completedTiles = [];

        while (count($tilesToProcess) > 0) {
            $tile = array_pop($tilesToProcess);
            unset($unprocessedTiles[$tile->id]);

            if (isset($completedTiles[$tile->id])) {
                continue;
            }

            foreach ($unprocessedTiles as $unprocessedTile) {
                $tile->linkIfMatches($unprocessedTile);

                if (count($tile->links) === 4) {
                    break;
                }
            }

            $completedTiles[$tile->id] = true;

            foreach ($tile->links as $linkedTile) {
                if (!isset($completedTiles[$linkedTile->id])) {
                    $tilesToProcess[$linkedTile->id] = $linkedTile;
                }
            }
        }

        return $tiles;
    }

    private function buildImage(ImageTile $topLeftImage): Image
    {
        $image = $topLeftImage->image->image;
        $leftmostTile = $topLeftImage;
        $currentTile = $topLeftImage;
        $indexDelta = 0;

        do {
            while (isset($currentTile->links['R'])) {
                $rightTile = $currentTile->links['R'];

                foreach ($rightTile->image->image as $key => $line) {
                    $image[$indexDelta + $key] = array_merge($image[$indexDelta + $key], $line);
                }

                $currentTile = $rightTile;
            }

            $indexDelta = count($image);

            if (!isset($leftmostTile->links['B'])) {
                break;
            }

            $bottomTile = $leftmostTile->links['B'];

            foreach ($bottomTile->image->image as $key => $line) {
                $image[$indexDelta + $key] = $line;
            }

            $leftmostTile = $leftmostTile->links['B'];
            $currentTile = $leftmostTile;
        } while (true);

        return new Image($image);
    }
}
