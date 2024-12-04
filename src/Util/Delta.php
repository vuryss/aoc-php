<?php

declare(strict_types=1);

namespace App\Util;

readonly class Delta
{
    public const array SURROUNDING = [
        [-1, -1], // Top left
        [0, -1], // Top
        [1, -1], // Top right
        [-1, 0], // Left
        [1, 0], // Right
        [-1, 1], // Bottom left
        [0, 1], // Bottom
        [1, 1], // Bottom right
    ];

    public const array DIAGONAL = [
        [-1, -1], // Top left
        [1, -1], // Top right
        [1, 1], // Bottom right
        [-1, 1], // Bottom left
    ];

    public const array DIAGONAL_INCLUSIVE = [
        [0, 0], // Center
        [-1, -1], // Top left
        [1, -1], // Top right
        [1, 1], // Bottom right
        [-1, 1], // Bottom left
    ];
}
