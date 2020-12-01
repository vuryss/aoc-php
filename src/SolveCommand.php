<?php

declare(strict_types=1);

namespace App;

use App\Event\DayInterface;
use App\Event\EventDayRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

class SolveCommand extends Command
{
    private EventDayRegistry $eventDayRegistry;
    private InputResolver $inputDownloader;
    private OutputInterface $output;

    public function __construct(EventDayRegistry $eventDayRegistry, InputResolver $inputDownloader)
    {
        parent::__construct('solve');

        $this->eventDayRegistry = $eventDayRegistry;
        $this->inputDownloader = $inputDownloader;
    }

    protected function configure()
    {
        $this
            ->setName('solve')
            ->setDescription('Solve a given day of Advent of Code')
            ->addArgument('day', InputArgument::REQUIRED, 'Day to solve')
            ->addOption(
                'event',
                null,
                InputOption::VALUE_REQUIRED,
                'Which year\'s AoC to use.',
                2020
            )
            ->addOption(
                'test',
                null,
                InputOption::VALUE_NONE,
                'Run the tests before the final input.'
            )
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     * @throws Throwable
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->output = $output;
        $year = $input->hasOption('event') ? (int) $input->getOption('event') : 2020;
        $day = (int) $input->getArgument('day');
        $runTests = $input->getOption('test');

        $eventDay = $this->eventDayRegistry->getDayInYear($year, $day);

        if (!$eventDay) {
            $output->writeln('<error> Year ' . $year . ' Day ' . $day . ' not found!</error>');
            return Command::FAILURE;
        }

        $dayInput = $this->inputDownloader->getInputForYearAndDay($year, $day);

        if ($dayInput === null) {
            $output->writeln('<error> Could not get input for year ' . $year . ' day ' . $day . '!</error>');
            return Command::FAILURE;
        }

        $dayInput = trim($dayInput);

        if ($runTests) {
            $this->runTestsForDay($eventDay);
        } else {
            $formatter = $this->getHelper('formatter');

            $output->writeln(
                $formatter->formatBlock('Solving for user input', 'comment', true)
            );

            $start = microtime(true);
            $output->writeln('Part 1: ' . $eventDay->solvePart1($dayInput));
            $output->writeln('Execution time for part 1: ' . (microtime(true) - $start));

            $output->writeln('');

            $start = microtime(true);
            $output->writeln('Part 2: ' . $eventDay->solvePart2($dayInput));
            $output->writeln('Execution time for part 2: ' . (microtime(true) - $start));
        }

        return Command::SUCCESS;
    }

    private function runTestsForDay(DayInterface $eventDay)
    {
        $formatter = $this->getHelper('formatter');

        $this->output->writeln(
            $formatter->formatBlock('Executing tests for part 1', 'comment', true)
        );

        $this->runTests($eventDay->testPart1(), fn ($input) => $eventDay->solvePart1($input));

        $this->output->writeln(
            $formatter->formatBlock('Executing tests for part 2', 'comment', true)
        );

        $this->runTests($eventDay->testPart2(), fn ($input) => $eventDay->solvePart2($input));
    }

    private function runTests(iterable $tests, callable $solveFn)
    {
        $testNumber = 1;

        foreach ($tests as $expectedResult => $testInput) {
            $actualResult = $solveFn($testInput);

            if ($expectedResult === $actualResult) {
                $this->output->writeln('<info>Test ' . $testNumber++ . ' success!</info>');
            } else {
                $this->output->writeln(
                    '<error>'
                    . 'Test ' . $testNumber++ . ' failed!'
                    . ' Expected: ' . $expectedResult . ' received: ' . $actualResult
                    . '</error>'
                );
            }
        }
    }
}
