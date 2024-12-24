<?php

declare(strict_types=1);

namespace App\Event\Year2024;

use App\Event\DayInterface;
use App\Event\Year2024\Day24\Circuit;

class Day24 implements DayInterface
{
    public function testPart1(): iterable
    {
        yield '4' => <<<'INPUT'
            x00: 1
            x01: 1
            x02: 1
            y00: 0
            y01: 1
            y02: 0
            
            x00 AND y00 -> z00
            x01 XOR y01 -> z01
            x02 OR y02 -> z02
            INPUT;

        yield '2024' => <<<'INPUT'
            x00: 1
            x01: 0
            x02: 1
            x03: 1
            x04: 0
            y00: 1
            y01: 1
            y02: 1
            y03: 1
            y04: 1
            
            ntg XOR fgs -> mjb
            y02 OR x01 -> tnw
            kwq OR kpj -> z05
            x00 OR x03 -> fst
            tgd XOR rvg -> z01
            vdt OR tnw -> bfw
            bfw AND frj -> z10
            ffh OR nrd -> bqk
            y00 AND y03 -> djm
            y03 OR y00 -> psh
            bqk OR frj -> z08
            tnw OR fst -> frj
            gnj AND tgd -> z11
            bfw XOR mjb -> z00
            x03 OR x00 -> vdt
            gnj AND wpb -> z02
            x04 AND y00 -> kjc
            djm OR pbm -> qhw
            nrd AND vdt -> hwm
            kjc AND fst -> rvg
            y04 OR y02 -> fgs
            y01 AND x02 -> pbm
            ntg OR kjc -> kwq
            psh XOR fgs -> tgd
            qhw XOR tgd -> z09
            pbm OR djm -> kpj
            x03 XOR y03 -> ffh
            x00 XOR y04 -> ntg
            bfw OR bqk -> z06
            nrd XOR fgs -> wpb
            frj XOR qhw -> z04
            bqk OR frj -> z07
            y03 OR x01 -> nrd
            hwm AND bqk -> z03
            tgd XOR rvg -> z12
            tnw OR pbm -> gnj
            INPUT;
    }

    public function testPart2(): iterable
    {
        return [];
    }

    public function solvePart1(string $input): string|int
    {
        $blocks = explode("\n\n", $input);
        $wires = [];

        foreach (explode("\n", $blocks[0]) as $line) {
            [$wire, $value] = explode(': ', $line);
            $wires[$wire] = (int) $value;
        }

        $connections = [];

        foreach (explode("\n", $blocks[1]) as $line) {
            [$inputs, $output] = explode(' -> ', $line);
            $connections[$output] = explode(' ', $inputs);
        }

        $circuit = new Circuit(connections: $connections, wires: $wires);

        return $circuit->getOutput();
    }

    public function solvePart2(string $input): string|int
    {
        $blocks = explode("\n\n", $input);

        $connections = [];

        foreach (explode("\n", $blocks[1]) as $line) {
            [$inputs, $output] = explode(' -> ', $line);
            $connections[$output] = explode(' ', $inputs);
        }

        // First part:
        // Check on which z the calculation is wrong
        // For that z check if there is any XOR that has been switched to '1', which makes it the correct XOR for that z
        // Swap those outputs
        $xorSwapped = true;
        $swapped = [];

        while ($xorSwapped) {
            $xorSwapped = false;
            $xorWires = array_filter(array_keys($connections), fn ($output) => $connections[$output][1] === 'XOR');

            for ($i = 1; $i < 45; $i++) {
                $wires = [];
                $expectedResult = (2 ** $i - 1) * 2;

                for ($j = 0; $j < 45; $j++) {
                    $bit = str_pad("$j", 2, '0', STR_PAD_LEFT);
                    $wires['x' . $bit] = $j < $i ? 1 : 0;
                    $wires['y' . $bit] = $j < $i ? 1 : 0;
                }

                $circuit = new Circuit(connections: $connections, wires: $wires);

                if ($circuit->getOutput() !== $expectedResult) {
                    $zWire = 'z' . str_pad("$i", 2, '0', STR_PAD_LEFT);

                    foreach ($xorWires as $wire) {
                        $signal = $circuit->getWireSignal($wire);

                        if ($wire[0] !== 'z' && $signal === 1) {
                            $temp = $connections;
                            $connections[$zWire] = $connections[$wire];
                            $connections[$wire] = $temp[$zWire];
                            $swapped = [...$swapped, $wire, $zWire];
                            $xorSwapped = true;

                            continue 3;
                        }
                    }
                }
            }
        }

        // Second part:
        // Each z wire (except first and last) should have one of the inputs be (x xor y) of the same bit
        // If not, find the correct (x xor y) and swap it with the wrong input (which references x or y directly)
        foreach ($connections as $output => $input) {
            if ($output[0] !== 'z' || $output === 'z00' || $output === 'z45') {
                continue;
            }

            $bit = substr($output, 1);
            $input1 = $connections[$input[0]];
            $input2 = $connections[$input[2]];

            if (
                $input1 === ['x' . $bit, 'XOR', 'y' . $bit] ||
                $input1 === ['y' . $bit, 'XOR', 'x' . $bit] ||
                $input2 === ['x' . $bit, 'XOR', 'y' . $bit] ||
                $input2 === ['y' . $bit, 'XOR', 'x' . $bit]
            ) {
                continue;
            }

            // Check which of the 2 inputs referenced x and y directly
            $wrongInput = $input1[0][0] === 'x' || $input1[0][0] === 'y' ? $input[0] : $input[2];

            // Find the XOR of the same bit
            $match1 = ['x' . $bit, 'XOR', 'y' . $bit];
            $match2 = ['y' . $bit, 'XOR', 'x' . $bit];

            foreach ($connections as $o => $i) {
                if ($i === $match1 || $i === $match2) {
                    $temp = $connections;
                    $connections[$o] = $connections[$wrongInput];
                    $connections[$wrongInput] = $temp[$o];
                    $swapped = [...$swapped, $wrongInput, $o];
                }
            }
        }

        sort($swapped);

        return implode(',', $swapped);
    }
}
