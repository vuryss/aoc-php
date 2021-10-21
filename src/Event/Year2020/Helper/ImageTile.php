<?php

declare(strict_types=1);

namespace App\Event\Year2020\Helper;

class ImageTile
{
    public const KEY_ROTATE = [
        'T' => 'R',
        'B' => 'L',
        'L' => 'T',
        'R' => 'B',
    ];

    public const KEY_FLIP_H = [
        'T' => 'T',
        'B' => 'B',
        'L' => 'R',
        'R' => 'L',
    ];

    public const MATCH_SIDE = [
        'T' => 'B',
        'B' => 'T',
        'L' => 'R',
        'R' => 'L',
    ];

    public array $borders;
    public array $reverseBorders;

    /**
     * @var array<string, ImageTile>
     */
    public array $links = [];
    public array $linkedIds = [];

    public Image $image;

    public function __construct(
        public int $id,
        array $image,
    ) {
        $this->borders = [
            'T' => implode('', $image[0]),
            'B' => implode('', $image[array_key_last($image)]),
            'L' => implode('', array_column($image, 0)),
            'R' => implode('', array_column($image, array_key_last($image[0]))),
        ];
        $this->calculateReverseBorders();

        $image = array_slice($image, 1, -1);

        foreach ($image as $key => $line) {
            $image[$key] = array_slice($line, 1, -1);
        }

        $this->image = new Image($image);
    }

    public function linkIfMatches(ImageTile $tile): void
    {
        if (
            count($tile->links) === 4
            || in_array($tile->id, $this->linkedIds, true)
            || in_array($this->id, $tile->linkedIds, true)
        ) {
            return;
        }

        foreach ($this->borders as $side => $border) {
            foreach ($tile->borders as $tileSide => $tileBorder) {
                if ($border === $tileBorder || $border === $tile->reverseBorders[$tileSide]) {
                    for ($i = 1; $i <= 2; $i++) {
                        for ($j = 1; $j <= 4; $j++) {
                            if ($tile->borders[self::MATCH_SIDE[$side]] === $border) {
                                break 2;
                            }
                            $tile->rotateRight();
                        }
                        $tile->horizontalFlip();
                    }

                    $this->links[$side] = $tile;
                    $this->linkedIds[] = $tile->id;

                    $tile->links[self::MATCH_SIDE[$side]] = $this;
                    $tile->linkedIds[] = $this->id;

                    return;
                }
            }
        }
    }

    public function rotateRight(): void
    {
        $this->image->rotateRight();
        $this->borders = [
            'T' => strrev($this->borders['L']),
            'B' => strrev($this->borders['R']),
            'L' => $this->borders['B'],
            'R' => $this->borders['T'],
        ];
        $this->calculateReverseBorders();

        // Rotate links
        $newLinks = [];

        foreach ($this->links as $side => $tile) {
            $newLinks[self::KEY_ROTATE[$side]] = $tile;
        }

        $this->links = $newLinks;
    }

    public function horizontalFlip(): void
    {
        $this->image->horizontalFlip();
        $this->borders = [
            'T' => strrev($this->borders['T']),
            'B' => strrev($this->borders['B']),
            'L' => $this->borders['R'],
            'R' => $this->borders['L'],
        ];
        $this->calculateReverseBorders();

        // Flip links
        $newLinks = [];

        foreach ($this->links as $side => $tile) {
            $newLinks[self::KEY_FLIP_H[$side]] = $tile;
        }

        $this->links = $newLinks;
    }

    private function calculateReverseBorders(): void
    {
        $this->reverseBorders = [
            'T' => strrev($this->borders['T']),
            'B' => strrev($this->borders['B']),
            'L' => strrev($this->borders['L']),
            'R' => strrev($this->borders['R']),
        ];
    }
}