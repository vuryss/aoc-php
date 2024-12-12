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

    // X, Y
    public const array DIAGONAL_INCLUSIVE = [
        [0, 0], // Center
        [-1, -1], // Top left
        [1, -1], // Top right
        [1, 1], // Bottom right
        [-1, 1], // Bottom left
    ];

    // Edges with 3 points (the diagonal cell and the 2 adjacent to it points)
    // X, Y
    public const array EDGE_CAPS = [
        [[-1, 0], [-1, -1], [0, -1]], // Top Left
        [[0, -1], [1, -1], [1, 0]], // Top Right
        [[1, 0], [1, 1], [0, 1]], // Bottom Right
        [[0, 1], [-1, 1], [-1, 0]], // Bottom Left
    ];
}
