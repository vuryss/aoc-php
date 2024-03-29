# Advent of code solutions in PHP

## Events added

- [2020](https://adventofcode.com/2020)
- [2021](https://adventofcode.com/2021) (default)

## Requirements

- PHP 8.0
- GMP, BCMath and DS extensions
- Composer
- Advent of Code account

## Installation

Just clone the repo and run `composer install`

Put your session token under: `.env.local` file to be able to download inputs

## Usage

### Generate class for solution for given event & day
`./app generate` - Generate for current year, current day

`./app generate 15` - Generate for current year, day 15

`./app generate 21 --event 2020` - Generate for event 2020, day 21

`./app generate --event 2016` - Generate for event 2016, current day (if between 1 and 25)

### Test and execute solutions
`./app solve 1` - to execute with AoC user input for current year's event

`./app solve 1 --year=2019` - to execute with user input for previous event

`./app solve 1 --test` - to execute with tests inputs (defined in the wrapper class)
