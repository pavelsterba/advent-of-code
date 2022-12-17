<?php

declare(strict_types=1);

namespace AdventOfCode\Command;

use AdventOfCode\Generator\SolutionGenerator;
use AdventOfCode\Utils\HttpClient;
use AdventOfCode\Utils\Printer;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;

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
        $this->addOption("force", "f", InputOption::VALUE_NONE, "Download input without cache");
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $year = strval($input->getOption("year"));
        $day = strval($input->getArgument("day"));
        $fromCache = true;
        $fs = new Filesystem();
        /** @var \Symfony\Component\Console\Helper\FormatterHelper $formatter */
        $formatter = $this->getHelper('formatter');
        $printer = new Printer($output, $formatter);
        $client = new HttpClient();
        $cache = new FilesystemAdapter();

        $printer->logo();
        $printer->justify('$day = ' . $day . ';', '$year = ' . $year . ';', $printer->getLogoWidth(), 'fg=yellow');
        $output->writeln("");

        try {
            $url = sprintf(self::INPUT_URL, $year, $day);
            $key = $year . '_' . $day;

            if ($input->getOption('force')) {
                $cache->delete($key);
            }

            $inputData = $cache->get($key, function (ItemInterface $item) use ($client, $url, &$fromCache) {
                $item->expiresAfter(null);
                $fromCache = false;
                return $client->get($url);
            });

            if ($fromCache) {
                $printer->warningLine("Input is loaded from cache. Use <fg=gray>--force</> to download it again.", "CACHE");
                $output->writeln("");
            }

            $dayDir = getcwd() . DIRECTORY_SEPARATOR . $year . DIRECTORY_SEPARATOR . "day-" . $day;

            try {
                $fs->mkdir($dayDir);
            } catch (IOExceptionInterface $e) {
                $messages = [
                    "Directory for puzzle cannot be created in:",
                    $e->getPath(),
                ];

                $printer->error($messages);
                return Command::FAILURE;
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
        } catch (HttpExceptionInterface $e) {
            $messages = [
                "Input data cannot be downloaded",
                sprintf("[%s] %s", $e->getResponse()->getStatusCode(), $e->getResponse()->getInfo()['url']),
            ];
            $printer->error($messages);
            return Command::FAILURE;
        }
    }
}
