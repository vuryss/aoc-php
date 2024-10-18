# Advent of code solutions in PHP

## Events added

- [2020](https://adventofcode.com/2020)
- [2021](https://adventofcode.com/2021) (default)

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
`./app solve -d 1` - to execute with AoC user input for current year's event

`./app solve -d 1 -y 2019` - to execute with user input for previous event

`./app solve -d 1 --test` - to execute with tests inputs (defined in the wrapper class)

---

This repo does follow the automation guidelines on the /r/adventofcode community wiki https://www.reddit.com/r/adventofcode/wiki/faqs/automation. Specifically:

- Once inputs are downloaded, they are cached locally
- If you suspect your input is corrupted, you can manually request a fresh copy by deleting the input file: `/inputs/{year}/{day}`
- The User-Agent header in requests to AoC is set to me since I maintain this repo :)
