<?php

namespace AdventOfCode\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'input', description: "Download input data for challenge")]
class InputCommand extends Command
{
    const INPUT_URL = "https://adventofcode.com/%s/day/%s/input";

    protected function configure()
    {
        $this->addOption("year", "y", InputOption::VALUE_REQUIRED, "Which year should be downloaded", isset($_ENV["AOC_YEAR"]) ? $_ENV["AOC_YEAR"] : date("Y"));
        $this->addOption("day", "d", InputOption::VALUE_REQUIRED, "Which day should be downloaded", date("j"));
        $this->addOption("output", "o", InputOption::VALUE_NONE, "Print input data");
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $year = $input->getOption("year");
        $day = $input->getOption("day");
        $cwd = getcwd();

        $options = [
            'http' => [
                'method' => "GET",
                'header' => "Cookie: session=" . $_ENV["AOC_SESSION"],
            ]
        ];

        $context = stream_context_create($options);
        $url = sprintf(self::INPUT_URL, $year, $day);

        $inputData = @file_get_contents($url, false, $context);

        if (!$input->getOption("output")) {
            $output->writeln(sprintf("<options=bold>Advent of Code %s</> - Day <comment>%s</comment>", $year, $day));
            $output->writeln("-=-=-=-=-=-=-=-=-=-");

            $yearFolder = $cwd . DIRECTORY_SEPARATOR . $year;
            if (!file_exists($yearFolder)) {
                $output->writeln(sprintf("Folder for year <comment>%s</comment> does not exist", $year));
                mkdir($yearFolder);
                $output->writeln(sprintf("<info>Created</info> in %s", $yearFolder));
            }

            $dayFolder = $yearFolder . DIRECTORY_SEPARATOR . "day-" . $day;
            if (!file_exists($dayFolder)) {
                $output->writeln(sprintf("Folder for day <comment>%s</comment> does not exist", $day));
                mkdir($dayFolder);
                $output->writeln(sprintf("<info>Created</info> in %s", $dayFolder));
            }

            $output->writeln("");
            if ($inputData !== false) {
                $inputFile = $dayFolder . DIRECTORY_SEPARATOR . "input.txt";
                $saved = @file_put_contents($inputFile, $inputData);

                if ($saved !== false) {
                    $output->writeln("<info>Input data successfully downloaded</info>");
                    return Command::SUCCESS;
                } else {
                    $output->writeln("<error>Input data cannot be saved</error>");
                    return Command::FAILURE;
                }
            } else {
                $output->writeln("<error>Input data cannot be downloaded</error>");
                return Command::FAILURE;
            }
        } else {
            $output->writeln($inputData);
            return Command::SUCCESS;
        }
    }
}
