<?php

declare(strict_types=1);

namespace App\Event\Year2020;

use App\Event\Day;

class Day4 extends Day
{
    const YEAR = 2020;
    const DAY = 4;

    const REGEX = [
        'byr' => '/^(19[2-9][0-9])|(200[0-2])$/', // 1920 to 2002
        'iyr' => '/^20(1\d|20)$/', // 2010 to 2020
        'eyr' => '/^20(2\d|30)$/', // 2020 to 2030
        'hgt' => '/^((59|6\d|7[0-6])in)|(((1[5-8]\d)|(19[0-3]))cm)$/', // 59in to 79in or 150cm to 193cm
        'hcl' => '/^#[0-9abcdef]{6}$/',
        'ecl' => '/^amb|blu|brn|gry|grn|hzl|oth$/',
        'pid' => '/^\d{9}$/',
    ];

    public function testPart1(): iterable
    {
        yield '2' => <<<'INPUT'
            ecl:gry pid:860033327 eyr:2020 hcl:#fffffd
            byr:1937 iyr:2017 cid:147 hgt:183cm
            
            iyr:2013 ecl:amb cid:350 eyr:2023 pid:028048884
            hcl:#cfa07d byr:1929
            
            hcl:#ae17e1 iyr:2013
            eyr:2024
            ecl:brn pid:760753108 byr:1931
            hgt:179cm
            
            hcl:#cfa07d eyr:2025 pid:166559648
            iyr:2011 ecl:brn hgt:59in
            INPUT;
    }

    public function testPart2(): iterable
    {
        yield '0' => <<<'INPUT'
            eyr:1972 cid:100
            hcl:#18171d ecl:amb hgt:170 pid:186cm iyr:2018 byr:1926
            
            iyr:2019
            hcl:#602927 eyr:1967 hgt:170cm
            ecl:grn pid:012533040 byr:1946
            
            hcl:dab227 iyr:2012
            ecl:brn hgt:182cm pid:021572410 eyr:2020 byr:1992 cid:277
            
            hgt:59cm ecl:zzz
            eyr:2038 hcl:74454a iyr:2023
            pid:3556412378 byr:2007
            INPUT;

        yield '4' => <<<'INPUT'
            pid:087499704 hgt:74in ecl:grn iyr:2012 eyr:2030 byr:1980
            hcl:#623a2f
            
            eyr:2029 ecl:blu cid:129 byr:1989
            iyr:2014 pid:896056539 hcl:#a97842 hgt:165cm
            
            hcl:#888785
            hgt:164cm byr:2001 iyr:2015 cid:88
            pid:545766238 ecl:hzl
            eyr:2022
            
            iyr:2010 hgt:158cm hcl:#b6652a ecl:blu byr:1944 eyr:2021 pid:093154719
            INPUT;

    }

    public function solvePart1(string $input): string
    {
        $passports = $this->parsePassportsFromInput($input);
        $valid = 0;

        foreach ($passports as $passport) {
            foreach (self::REGEX as $key => $validations) {
                if (!isset($passport[$key])) {
                    continue 2;
                }
            }

            $valid++;
        }

        return (string) $valid;
    }

    public function solvePart2(string $input): string
    {
        $passports = $this->parsePassportsFromInput($input);
        $valid = 0;

        foreach ($passports as $passport) {
            foreach (self::REGEX as $key => $regex) {
                if (!isset($passport[$key]) || preg_match($regex, $passport[$key]) !== 1) {
                    continue 2;
                }
            }

            $valid++;
        }

        return (string) $valid;
    }

    private function parsePassportsFromInput(string $input): array
    {
        $passportsData = explode("\n\n", $input);
        $passports = [];

        foreach ($passportsData as $passportsDatum) {
            $fields = preg_split('/[\s\n]+/', $passportsDatum);
            $data = [];

            foreach ($fields as $field) {
                [$field, $value] = explode(':', $field);
                $data[$field] = $value;
            }

            $passports[] = $data;
        }

        return $passports;
    }
}
