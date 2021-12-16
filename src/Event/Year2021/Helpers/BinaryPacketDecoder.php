<?php

declare(strict_types=1);

namespace App\Event\Year2021\Helpers;

class BinaryPacketDecoder
{
    private const TYPE_LITERAL_VALUE = 4;

    private int $versionTotal = 0;
    private int $index = 0;

    public function __construct(private string $binary)
    {
    }

    public function getVersionsSum(): int
    {
        return $this->versionTotal;
    }

    public function decodePacket(): int
    {
        $this->versionTotal += bindec(substr($this->binary, $this->index, 3));
        $typeID = bindec(substr($this->binary, $this->index + 3, 3));
        $this->index += 6;

        if ($typeID === self::TYPE_LITERAL_VALUE) {
            $literalValue = '';

            do {
                $literalValue .= substr($this->binary, $this->index + 1, 4);
                $this->index += 5;
            } while ($this->binary[$this->index - 5] === '1');

            return bindec($literalValue);
        }

        $lengthTypeID = $this->binary[$this->index++];

        if ($lengthTypeID === '0') {
            $numberOfBits = bindec(substr($this->binary, $this->index, 15));
            $this->index += 15;
            return $this->applyOperation($typeID, $this->parseNumberOfBits($numberOfBits));
        }

        $numberOfSubPackets = bindec(substr($this->binary, $this->index, 11));
        $this->index += 11;
        return $this->applyOperation($typeID, $this->parseNumberOfPackets($numberOfSubPackets));
    }

    private function parseNumberOfBits(int $numberOfBits): array
    {
        $values = [];
        $startBit = $this->index;

        while ($this->index - $startBit < $numberOfBits) {
            $values[] = $this->decodePacket();
        }

        return $values;
    }

    private function parseNumberOfPackets(int $numberOfPackets): array
    {
        $values = [];

        while ($numberOfPackets > 0) {
            $values[] = $this->decodePacket();
            $numberOfPackets--;
        }

        return $values;
    }

    private function applyOperation(int $operation, array $numbers): int
    {
        return match ($operation) {
            0 => array_sum($numbers),
            1 => array_product($numbers),
            2 => min($numbers),
            3 => max($numbers),
            5 => (int) ($numbers[0] > $numbers[1]),
            6 => (int) ($numbers[0] < $numbers[1]),
            7 => (int) ($numbers[0] === $numbers[1]),
            default => current($numbers),
        };
    }

}
