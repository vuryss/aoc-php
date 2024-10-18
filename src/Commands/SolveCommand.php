<?php

declare(strict_types=1);

namespace App\Commands;

use App\Event\DayInterface;
use App\Event\EventDayRegistry;
use App\InputResolver;
use App\PuzzleId;
use Exception;
use LogicException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Helper\TableCellStyle;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand('solve', 'Solve a given day of Advent of Code.')]
class SolveCommand extends Command
{
    private OutputInterface $output;

    public function __construct(
        private readonly EventDayRegistry $eventDayRegistry,
        private readonly InputResolver $inputDownloader
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $currentYear = (int) date('Y');

        $this
            ->addOption(
                name: 'day',
                shortcut: 'd',
                mode: InputArgument::OPTIONAL,
                description: 'Day to solve',
            )
            ->addOption(
                name: 'event',
                shortcut: 'y',
                mode: InputOption::VALUE_REQUIRED,
                description: 'Which year\'s AoC to use.',
                default: 12 === (int) date('n') ? $currentYear : $currentYear - 1,
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
        $this->validateInput($input);
        $puzzleId = new PuzzleId(
            event: (int) $input->getOption('event'),
            day: null !== $input->getOption('day') ? (int) $input->getOption('day') : null
        );
        $runTests = (bool) $input->getOption('test');

        if ($runTests) {
            $this->output->writeln('<comment> Running with test input </comment>');
        } else {
            $this->output->writeln('<comment> Running with puzzle input </comment>');
        }

        if (null === $puzzleId->day) {
            for ($day = 1; $day <= 25; $day++) {
                $this->solvePuzzle(new PuzzleId($puzzleId->event, $day), $runTests);
            }
        } else {
            $this->solvePuzzle($puzzleId, $runTests);
        }

        return Command::SUCCESS;
    }

    /**
     * @throws Exception
     */
    private function runTestsForDay(PuzzleId $puzzleId, DayInterface $eventDay): void
    {
        $table = new Table($this->output->section());
        $table->addRow([new TableCell('Day ' . $puzzleId->day, ['colspan' => 3, 'style' => new TableCellStyle(['align' => 'center'])])]);
        $table->addRow(new TableSeparator());
        $table->render();

        $this->runTests($eventDay->testPart1(), fn ($input) => (string) $eventDay->solvePart1($input), $table, 'Part 1');
        $table->addRow(new TableSeparator());
        $this->runTests($eventDay->testPart2(), fn ($input) => (string) $eventDay->solvePart2($input), $table, 'Part 2');
    }

    private function runTests(iterable $tests, callable $solveFn, Table $table, string $part): void
    {
        $testNumber = 1;

        foreach ($tests as $expectedResult => $testInput) {
            $actualResult = $solveFn($testInput);

            if ($expectedResult === $actualResult) {
                $table->appendRow([$part, 'Test ' . $testNumber, '<info>Success</info>']);
            } else {
                $table->appendRow([
                    $part,
                    'Test ' . $testNumber,
                    '<error>Expected: ' . $expectedResult . ' Received: ' . $actualResult . '</error>'
                ]);
            }
        }
    }

    private function validateInput(InputInterface $input): void
    {
        if (!ctype_digit($input->getOption('event'))) {
            throw new LogicException('Invalid event year given. Allowed values are between 2015 and ' . date('Y'));
        }

        if (null !== $input->getOption('day') && !ctype_digit($input->getOption('day'))) {
            throw new LogicException('Invalid event day given. Allowed values are between 1 and 25');
        }
    }

    private function solvePuzzle(PuzzleId $puzzleId, bool $runTests): void
    {
        $eventDay = $this->eventDayRegistry->getDayInYear($puzzleId->event, $puzzleId->day);

        if (!$eventDay) {
            $this->output->writeln('<error> Year ' . $puzzleId->event . ' Day ' . $puzzleId->day . ' not found!</error>');
            return;
        }

        $dayInput = $this->inputDownloader->getInputForYearAndDay($puzzleId->event, $puzzleId->day);

        if (null === $dayInput) {
            $this->output->writeln('<error> Could not get input for year ' . $puzzleId->event . ' day ' . $puzzleId->day . '!</error>');
            return;
        }

        $dayInput = trim($dayInput);

        if ($runTests) {
            $this->runTestsForDay($puzzleId, $eventDay);
            return;
        }

        $table = new Table($this->output->section());
        $table->addRow([new TableCell('Day ' . $puzzleId->day, ['colspan' => 3, 'style' => new TableCellStyle(['align' => 'center'])])]);
        $table->addRow(new TableSeparator());
        $table->render();

        $start = microtime(true);
        $part1Result = (string) $eventDay->solvePart1($dayInput);
        $part1ExecutionTime = microtime(true) - $start;

        $table->appendRow(['Part 1', str_pad($part1Result, 100, ' '), number_format($part1ExecutionTime, 5, '.', '').'s']);

        $part2Result = (string) $eventDay->solvePart2($dayInput);
        $part2ExecutionTime = microtime(true) - $start - $part1ExecutionTime;

        $table->appendRow(['Part 2', str_pad($part2Result, 100, ' '), number_format($part2ExecutionTime, 5, '.', '').'s']);
    }
}
