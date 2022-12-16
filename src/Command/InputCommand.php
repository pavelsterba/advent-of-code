<?php

declare(strict_types=1);

namespace AdventOfCode\Command;

use AdventOfCode\Generator\SolutionGenerator;
use AdventOfCode\Utils\Printer;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;

#[AsCommand(name: 'input', description: "Download input data for puzzle")]
class InputCommand extends Command
{
    const INPUT_URL = "https://adventofcode.com/%s/day/%s/input";
    const PUZZLE_URL = "https://adventofcode.com/%s/day/%s";
    const INPUT_FILE_NAME = "input.txt";
    const INPUT_TEST_FILE_NAME = "input-test.txt";

    protected function configure(): void
    {
        $this->addArgument("day", InputArgument::REQUIRED, "Which day to download");
        $this->addOption("year", "y", InputOption::VALUE_REQUIRED, "Which year should be downloaded", isset($_ENV["AOC_YEAR"]) ? $_ENV["AOC_YEAR"] : date("Y"));
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $year = strval($input->getOption("year"));
        $day = strval($input->getArgument("day"));
        $fs = new Filesystem();
        /** @var \Symfony\Component\Console\Helper\FormatterHelper $formatter */
        $formatter = $this->getHelper('formatter');
        $printer = new Printer($output, $formatter);

        $printer->logo();
        $printer->justify('$day = ' . $day . ';', '$year = ' . $year . ';', $printer->getLogoWidth(), 'fg=yellow');
        $output->writeln("");

        $options = [
            'http' => [
                'method' => "GET",
                'header' => "User-Agent: https://github.com/pavelsterba/advent-of-code by email@pavelsterba.com\r\nCookie: session=" . $_ENV["AOC_SESSION"],
            ]
        ];

        $context = stream_context_create($options);
        $url = sprintf(self::INPUT_URL, $year, $day);

        $inputData = @file_get_contents($url, false, $context);

        if ($inputData !== false) {
            $dayDir = getcwd() . DIRECTORY_SEPARATOR . $year . DIRECTORY_SEPARATOR . "day-" . $day;

            try {
                $fs->mkdir($dayDir);
            } catch (IOExceptionInterface $e) {
                $messages = [
                    "Directory for puzzle cannot be created in:",
                    $e->getPath(),
                ];

                $printer->error($messages);
            }

            $inputFile = $dayDir . DIRECTORY_SEPARATOR . self::INPUT_FILE_NAME;
            $inputTestFile = $dayDir . DIRECTORY_SEPARATOR . self::INPUT_TEST_FILE_NAME;

            try {
                $fs->dumpFile($inputFile, $inputData);
                $fs->touch($inputTestFile);

                $generator = new SolutionGenerator();
                $solutionFile = $dayDir . DIRECTORY_SEPARATOR . "solution.php";
                $fs->dumpFile($solutionFile, "<?php\n\n" . $generator->generate());

                $output->writeln("<options=bold>Link to puzzle:</>");
                $printer->link(sprintf(self::PUZZLE_URL, $year, $day));
                $printer->success("Input data successfully downloaded");
                return Command::SUCCESS;
            } catch (IOExceptionInterface $e) {
                $messages = [
                    "Data cannot be saved in:",
                    $e->getPath(),
                ];

                $printer->error($messages);
            }
        } else {
            $printer->error("Input data cannot be downloaded");
            return Command::FAILURE;
        }
    }
}
