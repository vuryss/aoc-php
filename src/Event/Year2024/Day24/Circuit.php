<?php

declare(strict_types=1);

namespace App\Event\Year2024\Day24;

class Circuit
{
    public function __construct(
        readonly private array $connections,
        private array $wires,
    ) {
    }

    public function getOutput(): int
    {
        $zWires = array_filter(array_keys($this->connections), static fn ($wire): bool => str_starts_with($wire, 'z'));
        rsort($zWires);

        $number = implode('', array_map(fn ($wire) => $this->getWireSignal($wire), $zWires));

        return (int) base_convert($number, 2, 10);
    }

    public function getWireSignal(string $wire): int
    {
        if (isset($this->wires[$wire])) {
            return $this->wires[$wire];
        }

        $input = $this->connections[$wire];
        $wire1 = $this->getWireSignal($input[0]);
        $wire2 = $this->getWireSignal($input[2]);

        return $this->wires[$wire] = match ($input[1]) {
            'AND' => $wire1 & $wire2,
            'OR' => $wire1 | $wire2,
            'XOR' => $wire1 ^ $wire2,
        };
    }
}
