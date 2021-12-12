<?php

declare(strict_types=1);

namespace App\Event\Year2021\Helpers;

class Cave
{
    public array $adjacent = [];
    public bool $isStart;
    public bool $isEnd;
    public bool $isLower;

    public function __construct(public string $name)
    {
        $this->isStart = $this->name === 'start';
        $this->isEnd = $this->name === 'end';
        $this->isLower = strtolower($name) === $name;
    }
}
