<?php

declare(strict_types=1);

namespace App\Commands;

use App\AnswerResolver;
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
        private readonly InputResolver $inputDownloader,
        private readonly AnswerResolver $answerResolver,
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
            ->addOption(
                name: 'validate',
                mode: InputOption::VALUE_NONE,
                description: 'Validate the answers in already solved Advent of Code.',
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
        $validate = (bool) $input->getOption('validate');

        if ($runTests) {
            $this->executeTests($puzzleId);

            return Command::SUCCESS;
        }

        if ($validate) {
            $this->validateSolutions($puzzleId);

            return Command::SUCCESS;
        }

        $this->executeSolutions($puzzleId);

        return Command::SUCCESS;
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

    /**
     * @throws Exception
     */
    private function executeTests(PuzzleId $puzzleId): void
    {
        $this->output->writeln('<comment> Running with test input </comment>');

        if (null === $puzzleId->day) {
            for ($day = 1; $day <= 25; $day++) {
                $this->runTestsForDay(new PuzzleId($puzzleId->event, $day));
            }

            return;
        }

        $this->runTestsForDay($puzzleId);
    }

    /**
     * @throws Exception
     */
    private function runTestsForDay(PuzzleId $puzzleId): void
    {
        $solution = $this->getSolutionForPuzzle($puzzleId);

        $table = new Table($this->output->section());
        $table->addRow([new TableCell('Day ' . $puzzleId->day, ['colspan' => 3, 'style' => new TableCellStyle(['align' => 'center'])])]);
        $table->addRow(new TableSeparator());

        $this->runTests($solution->testPart1(), fn ($input) => (string) $solution->solvePart1($input), $table, 'Part 1');
        $table->addRow(new TableSeparator());
        $this->runTests($solution->testPart2(), fn ($input) => (string) $solution->solvePart2($input), $table, 'Part 2');

        $table->render();
    }

    private function runTests(iterable $tests, callable $solveFn, Table $table, string $part): void
    {
        $testNumber = 1;

        foreach ($tests as $expectedResult => $testInput) {
            $actualResult = $solveFn($testInput);

            if ($expectedResult === $actualResult) {
                $table->addRow([$part, 'Test ' . $testNumber++, '<info>Success</info>']);
            } else {
                $table->addRow([
                    $part,
                    'Test ' . $testNumber++,
                    '<error>Expected: ' . $expectedResult . ' Received: ' . $actualResult . '</error>'
                ]);
            }
        }
    }

    private function validateSolutions(PuzzleId $puzzleId): void
    {
        $this->output->writeln('<comment> Running validation with puzzle input </comment>');

        if (null === $puzzleId->day) {
            for ($day = 1; $day <= 25; $day++) {
                $this->validateSolutionForPuzzle(new PuzzleId($puzzleId->event, $day));
            }

            return;
        }

        $this->validateSolutionForPuzzle($puzzleId);
    }

    private function validateSolutionForPuzzle(PuzzleId $puzzleId): void
    {
        $solution = $this->getSolutionForPuzzle($puzzleId);
        $dayInput = $this->getInputForPuzzle($puzzleId);
        $part1CorrectAnswer = $this->getCorrectAnswer($puzzleId, 1);
        $part2CorrectAnswer = $this->getCorrectAnswer($puzzleId, 2);

        $dayInput = trim($dayInput);

        $part1Result = (string) $solution->solvePart1($dayInput);
        $part2Result = (string) $solution->solvePart2($dayInput);

        $table = new Table($this->output->section());
        $table->addRow([new TableCell('Day ' . $puzzleId->day, ['colspan' => 2, 'style' => new TableCellStyle(['align' => 'center'])])]);
        $table->addRow(new TableSeparator());

        $table->addRow([
            'Part 1',
            $part1Result === $part1CorrectAnswer ? '<info>Success</info>' : '<error>Failed, expected: ' . $part1CorrectAnswer . ' received: ' . $part1Result . '</error>',
        ]);
        $table->addRow([
            'Part 2',
            $part2Result === $part2CorrectAnswer ? '<info>Success</info>' : '<error>Failed, expected: ' . $part2CorrectAnswer . ' received: ' . $part2Result . '</error>',
        ]);

        $table->render();
    }

    private function executeSolutions(PuzzleId $puzzleId): void
    {
        $this->output->writeln('<comment> Running solution with puzzle input </comment>');

        if (null === $puzzleId->day) {
            for ($day = 1; $day <= 25; $day++) {
                $this->solvePuzzle(new PuzzleId($puzzleId->event, $day));
            }

            return;
        }

        $this->solvePuzzle($puzzleId);
    }

    private function solvePuzzle(PuzzleId $puzzleId): void
    {
        $solution = $this->getSolutionForPuzzle($puzzleId);
        $puzzleInput = $this->getInputForPuzzle($puzzleId);

        $table = new Table($this->output->section());
        $table->addRow([new TableCell('Day ' . $puzzleId->day, ['colspan' => 3, 'style' => new TableCellStyle(['align' => 'center'])])]);
        $table->addRow(new TableSeparator());

        $start = microtime(true);
        $part1Result = (string) $solution->solvePart1($puzzleInput);
        $part1ExecutionTime = microtime(true) - $start;

        $table->addRow(['Part 1', str_pad($part1Result, 100, ' '), number_format($part1ExecutionTime, 5, '.', '').'s']);

        $part2Result = (string) $solution->solvePart2($puzzleInput);
        $part2ExecutionTime = microtime(true) - $start - $part1ExecutionTime;

        $table->addRow(['Part 2', str_pad($part2Result, 100, ' '), number_format($part2ExecutionTime, 5, '.', '').'s']);

        $table->render();
    }

    private function getSolutionForPuzzle(PuzzleId $puzzleId): DayInterface
    {
        $eventDay = $this->eventDayRegistry->getDayInYear($puzzleId->event, $puzzleId->day);

        if (!$eventDay) {
            throw new \RuntimeException('Could not find solution for event ' . $puzzleId->event . ' day ' . $puzzleId->day);
        }

        return $eventDay;
    }

    private function getInputForPuzzle(PuzzleId $puzzleId): string
    {
        $dayInput = $this->inputDownloader->getInputForYearAndDay($puzzleId->event, $puzzleId->day);

        if (null === $dayInput) {
            throw new \RuntimeException('Could not get input for year ' . $puzzleId->event . ' day ' . $puzzleId->day);
        }

        return trim($dayInput);
    }

    private function getCorrectAnswer(PuzzleId $puzzleId, int $part): ?string
    {
        $answer = $this->answerResolver->getAnswersForYearAndDay($puzzleId->event, $puzzleId->day);

        if (null === $answer) {
            throw new \RuntimeException('Could not get answer for year ' . $puzzleId->event . ' day ' . $puzzleId->day);
        }

        return $answer['part' . $part];
    }
}
