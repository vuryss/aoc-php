<?php

declare(strict_types=1);

namespace App\Commands;

use App\Event\DayInterface;
use App\Event\EventDayRegistry;
use App\InputResolver;
use Exception;
use LogicException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand('solve', 'Solve a given day of Advent of Code.')]
class SolveCommand extends Command
{
    private OutputInterface $output;

    public function __construct(
        private EventDayRegistry $eventDayRegistry,
        private InputResolver $inputDownloader
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $currentYear = (int) date('Y');

        $this
            ->addArgument(
                name: 'day',
                mode: InputArgument::OPTIONAL,
                description: 'Day to solve',
                default: (int) date('j'),
            )
            ->addOption(
                name: 'event',
                mode: InputOption::VALUE_REQUIRED,
                description: 'Which year\'s AoC to use.',
                default: (int) date('n') === 12 ? $currentYear : $currentYear - 1,
            )
            ->addOption(
                name: 'test',
                mode: InputOption::VALUE_NONE,
                description: 'Run the tests instead of AoC user input.'
            )
        ;
    }

    /**
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->output = $output;
        [$year, $day] = $this->resolveEventDay($input);
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

    /**
     * @throws Exception
     */
    private function runTestsForDay(DayInterface $eventDay): void
    {
        $formatter = $this->getHelper('formatter');

        $this->output->writeln(
            $formatter->formatBlock('Executing tests for part 1', 'comment', true)
        );

        $this->runTests($eventDay->testPart1(), fn ($input) => (string) $eventDay->solvePart1($input));

        $this->output->writeln(
            $formatter->formatBlock('Executing tests for part 2', 'comment', true)
        );

        $this->runTests($eventDay->testPart2(), fn ($input) => (string) $eventDay->solvePart2($input));
    }

    private function runTests(iterable $tests, callable $solveFn): void
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

    private function resolveEventDay(InputInterface $input): array
    {
        $year = $input->getOption('event');
        $day = $input->getArgument('day');

        if ($year < 2015 || $year > (int) date('Y')) {
            throw new LogicException(
                sprintf('Invalid event year given. Allowed values are between 2015 and %s', date('Y'))
            );
        }

        if ($day < 1 || $day > 25 || (!is_int($day) && !ctype_digit($day))) {
            throw new LogicException('Invalid event day given. Allowed values are between 1 and 25');
        }

        return [(int) $year, (int) $day];
    }
}
