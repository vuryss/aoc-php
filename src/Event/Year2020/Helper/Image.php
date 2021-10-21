<?php

declare(strict_types=1);

namespace App\Event\Year2020\Helper;

class Image
{
    public function __construct(
        public array $image
    ) {
    }

    public function rotateRight(): void
    {
        $this->image = array_map('array_reverse', array_map(null, ...$this->image));
    }

    public function horizontalFlip(): void
    {
        $this->image = array_map('array_reverse', $this->image);
    }
}