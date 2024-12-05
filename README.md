# Advent of code solutions in PHP

## Events added

- [2020](https://adventofcode.com/2020) (all solutions available)
- [2021](https://adventofcode.com/2021) (all solutions available)
- [2022](https://adventofcode.com/2022) (not included - check java repo: https://github.com/vuryss/aoc-java)
- [2023](https://adventofcode.com/2023) (partially solved - full event @ java repo: https://github.com/vuryss/aoc-java)
- [2024](https://adventofcode.com/2024) (default)

For previous years, check other repositories in my profile.

## Requirements

- Advent of Code account
- Docker & Docker Compose

## Installation

Clone the repository.

Put your session token under: `.env.local` file to be able to download inputs

`docker-compose up -d` then `docker-compose exec app composer install`

## Usage

Execute those inside the docker container:

### Generate class for solution for given event & day
`./app generate` - Generate for current year, current day

`./app generate 15` - Generate for current year, day 15

`./app generate 21 --event 2020` - Generate for event 2020, day 21

`./app generate --event 2016` - Generate for event 2016, current day (if between 1 and 25)

### Test and execute solutions
If year is not given (-y xxxx or --event xxxx), then it takes current year if we're in December, otherwise it takes the last available event

`./app solve -d 1` - to execute day 1 with AoC user input (downloaded automatically)

`./app solve -d 1 -y 2019` - to execute day 1 with user input (downloaded automatically) for event 2019

`./app solve -d 1 --test` - to execute solution for day 1 with tests inputs (defined in the solution class)

`./app solve -y 2020 -d 1 --validate` - validate already solved day 1 year 2020 in AoC, downloading the answers and checking the solution against them

---

This repo does follow the automation guidelines on the /r/adventofcode community wiki https://www.reddit.com/r/adventofcode/wiki/faqs/automation. Specifically:

- Once inputs are downloaded, they are cached locally
- If you suspect your input is corrupted, you can manually request a fresh copy by deleting the input file: `/inputs/{year}/{day}`
- The User-Agent header in requests to AoC is set to me since I maintain this repo :)
