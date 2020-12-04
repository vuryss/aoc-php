<?php

declare(strict_types=1);

namespace App\Event\Year2020;

use App\Event\Day;

class Day4 extends Day
{
    const YEAR = 2020;
    const DAY = 4;

    const VALIDATIONS = [
        'byr' => ['regex' => '/^(\d{4})$/', 'min' => 1920, 'max' => 2002],
        'iyr' => ['regex' => '/^(\d{4})$/', 'min' => 2010, 'max' => 2020],
        'eyr' => ['regex' => '/^(\d{4})$/', 'min' => 2020, 'max' => 2030],
        'hgt' => [
            'or' => [
                ['regex' => '/^(\d{2})in$/', 'min' => 59, 'max' => 76],
                ['regex' => '/^(\d{3})cm$/', 'min' => 150, 'max' => 193],
            ],
        ],
        'hcl' => ['regex' => '/^#[0-9abcdef]{6}$/'],
        'ecl' => ['regex' => '/^amb|blu|brn|gry|grn|hzl|oth$/'],
        'pid' => ['regex' => '/^\d{9}$/'],
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
        $passports = explode("\n\n", $input);
        $valid = 0;

        foreach ($passports as $passport) {
            $fields = preg_split('/[\s\n]+/', $passport);
            $data = [];

            foreach ($fields as $field) {
                [$field, $value] = explode(':', $field);
                $data[$field] = $value;
            }

            foreach (self::VALIDATIONS as $key => $validations) {
                if (!isset($data[$key])) {
                    continue 2;
                }
            }

            $valid++;
        }

        return (string) $valid;
    }

    public function solvePart2(string $input): string
    {
        $passports = explode("\n\n", $input);
        $valid = 0;

        foreach ($passports as $passport) {
            $fields = preg_split('/[\s\n]+/', $passport);
            $data = [];

            foreach ($fields as $field) {
                [$field, $value] = explode(':', $field);
                $data[$field] = $value;
            }

            foreach (self::VALIDATIONS as $key => $validations) {
                if (!isset($data[$key]) || !$this->isValueValid($data[$key], $validations)) {
                    continue 2;
                }
            }

            $valid++;
        }

        return (string) $valid;
    }

    private function isValueValid(string $value, array $validations): bool
    {
        if (isset($validations['or'])) {
            $success = false;

            foreach ($validations['or'] as $subValidations) {
                $success = $success || $this->isValueValid($value, $subValidations);
            }

            return $success;
        }

        if (preg_match($validations['regex'], $value, $matches) !== 1) {
            return false;
        }

        $number = (int) ($matches[1] ?? 0);

        return !(
            isset($validations['min']) && $number < $validations['min']
            || isset($validations['max']) && $number > $validations['max']
        );
    }
}
