<?php

declare(strict_types=1);

namespace App\Event\Year2021;

use App\Event\DayInterface;
use App\Event\Year2021\Helpers\BinaryPacketDecoder;

class Day16 implements DayInterface
{
    public function testPart1(): iterable
    {
        yield '16' => <<<'INPUT'
            8A004A801A8002F478
            INPUT;

        yield '12' => <<<'INPUT'
            620080001611562C8802118E34
            INPUT;

        yield '23' => <<<'INPUT'
            C0015000016115A2E0802F182340
            INPUT;

        yield '31' => <<<'INPUT'
            A0016C880162017C3686B18A3D4780
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '3' => <<<'INPUT'
            C200B40A82
            INPUT;

        yield '54' => <<<'INPUT'
            04005AC33890
            INPUT;

        yield '7' => <<<'INPUT'
            880086C3E88112
            INPUT;

        yield '9' => <<<'INPUT'
            CE00C43D881120
            INPUT;

        yield '1' => <<<'INPUT'
            D8005AC2A8F0
            INPUT;

        yield '0' => <<<'INPUT'
            F600BC2D8F
            INPUT;

        yield '0' => <<<'INPUT'
            9C005AC2F8F0
            INPUT;

        yield '1' => <<<'INPUT'
            9C0141080250320F1802104A08
            INPUT;
    }

    public function solvePart1(string $input): string
    {
        $binary = gmp_strval(gmp_init($input, 16), 2);
        $binary = str_pad($binary, strlen($input) * 4, '0', STR_PAD_LEFT);

        $decoder = new BinaryPacketDecoder($binary);
        $decoder->decodePacket();

        return (string) $decoder->getVersionsSum();
    }

    public function solvePart2(string $input): string
    {
        $binary = gmp_strval(gmp_init($input, 16), 2);
        $binary = str_pad($binary, strlen($input) * 4, '0', STR_PAD_LEFT);

        $decoder = new BinaryPacketDecoder($binary);

        return (string) $decoder->decodePacket();
    }
}
