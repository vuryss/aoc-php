<?php

declare(strict_types=1);

namespace App\Commands;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

#[AsCommand('generate', 'Generates PHP class wrapper for solution to the given AoC day.')]
class GenerateCommand extends Command
{
    public function __construct(
        private Environment $templating,
        private string $eventsDirectory,
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
                description: 'Day to generate',
                default: (int) date('j'),
            )
            ->addOption(
                name: 'event',
                mode: InputOption::VALUE_REQUIRED,
                description: 'Which year\'s AoC to use.',
                default: (int) date('n') === 12 ? $currentYear : $currentYear - 1,
            )
        ;
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $year = $input->getOption('event');
        $day = $input->getArgument('day');

        $destinationFile = sprintf('%s/Year%s/Day%s.php', $this->eventsDirectory, $year, $day);

        if (file_exists($destinationFile)) {
            $output->writeln(
                sprintf(
                    '<error>Wrapper class for day %s of %s event already exists at %s</error>',
                    $day,
                    $year,
                    $destinationFile
                )
            );
            return Command::FAILURE;
        }

        $wrapperClassCode = $this->templating->render(
            'day-wrapper-class.twig',
            [
                'event' => $year,
                'day' => $day,
            ]
        );

        $bytesWritten = file_put_contents(
            $destinationFile,
            $wrapperClassCode
        );

        if (!$bytesWritten) {
            $output->writeln('<error>Could not write event day wrapper class</error>');
            return Command::FAILURE;
        }

        $output->writeln(
            sprintf(
                '<info>Wrapper class for day %s of %s event generated in %s</info>',
                $day,
                $year,
                $destinationFile
            )
        );
        return Command::SUCCESS;
    }
}
