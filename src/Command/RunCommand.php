<?php

namespace AdventOfCode\Command;

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
    protected function configure()
    {
        $this->addArgument("day", InputArgument::REQUIRED, "Which day to run");
        $this->addOption("year", "y", InputOption::VALUE_REQUIRED, "Which year are you solving");
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $year = $input->getOption("year") ?? $_ENV["AOC_YEAR"];
        $day = $input->getArgument("day");

        $solutionFile = getcwd() . DIRECTORY_SEPARATOR . $year . DIRECTORY_SEPARATOR . "day-" . $day . DIRECTORY_SEPARATOR . "solution.php";
        if (!file_exists($solutionFile)) {
            $output->writeln("<error>Solution file not found</error>");
            $output->writeLn($solutionFile);
            return Command::FAILURE;
        }

        require_once $solutionFile;

        $solution = new Solution($day);

        try {
            $first = $solution->first();
            $output->writeln("<info>Solution for first task:</info> " . $first);
        } catch (NotImplementedException) {
            $output->writeln("<comment>Solution for first task is not implemented yet.");
        }

        try {
            $second = $solution->second();
            $output->writeln("<info>Solution for second task:</info> " . $second);
        } catch (NotImplementedException) {
            $output->writeln("<comment>Solution for second task is not implemented yet.");
        }

        return Command::SUCCESS;
    }
}
