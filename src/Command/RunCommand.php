<?php

declare(strict_types=1);

namespace AdventOfCode\Command;

use AdventOfCode\Utils\Printer;
use AdventOfCode\Exception\NotImplementedException;
use Solution;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'run', description: "Run solution")]
class RunCommand extends Command
{
    protected function configure(): void
    {
        $this->addArgument("day", InputArgument::REQUIRED, "Which day to run");
        $this->addOption("year", "y", InputOption::VALUE_REQUIRED, "Which year are you solving");
        $this->addOption("first", "f", InputOption::VALUE_NONE, "Run first solution");
        $this->addOption("second", "s", InputOption::VALUE_NONE, "Run second solution");
        $this->addOption("test", "t", InputOption::VALUE_NONE, "Run on test data");
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $year = $input->getOption("year") ?? $_ENV["AOC_YEAR"];
        $day = $input->getArgument("day");
        $firstOnly = $input->getOption("first");
        $secondOnly = $input->getOption("second");
        $testMode = $input->getOption("test");

        /** @var \Symfony\Component\Console\Helper\FormatterHelper $formatter */
        $formatter = $this->getHelper('formatter');
        $printer = new Printer($output, $formatter);

        $printer->logo();
        $printer->justify('$day = ' . $day . ';', '$year = ' . $year . ';', $printer->getLogoWidth(), 'fg=yellow');
        $printer->blankLine();

        if ($testMode) {
            $printer->warningLine("Running with test data", "TEST");
            $printer->blankLine();
        }

        $solutionFile = getcwd() . DIRECTORY_SEPARATOR . $year . DIRECTORY_SEPARATOR . "day-" . $day . DIRECTORY_SEPARATOR . "solution.php";
        if (!file_exists($solutionFile)) {
            $messages = [
                "Solution file not found in:",
                $solutionFile,
            ];
            $printer->error($messages);
            return Command::FAILURE;
        }

        require_once $solutionFile;

        $solution = new Solution(intval($day), $testMode); // @phpstan-ignore-line

        if ($firstOnly || (!$firstOnly && !$secondOnly)) { // @phpstan-ignore-line
            try {
                $first = $solution->first(); // @phpstan-ignore-line
                $output->writeln("<info>Solution for first task:</info> " . $first);
            } catch (NotImplementedException) {
                $output->writeln("<comment>Solution for first task is not implemented yet.<comment>");
            }
        }

        if ($secondOnly || (!$firstOnly && !$secondOnly)) { // @phpstan-ignore-line
            try {
                $second = $solution->second(); // @phpstan-ignore-line
                $output->writeln("<info>Solution for second task:</info> " . $second);
            } catch (NotImplementedException) {
                $output->writeln("<comment>Solution for second task is not implemented yet.</comment>");
            }
        }

        return Command::SUCCESS;
    }
}
