<?php

declare(strict_types=1);

namespace AdventOfCode\Command;

use AdventOfCode\Utils\Printer;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ConfirmationQuestion;

#[AsCommand(name: 'dotenv', description: "Generate .env file")]
class DotEnvCommand extends Command
{
    protected function configure(): void
    {
        $this->addOption("year", "y", InputOption::VALUE_REQUIRED, "Which year should be added as current");
        $this->addOption("session", "s", InputOption::VALUE_REQUIRED, "Session token for Advent of Code");
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var \Symfony\Component\Console\Helper\QuestionHelper $helper */
        $helper = $this->getHelper("question");
        /** @var \Symfony\Component\Console\Helper\FormatterHelper $formatter */
        $formatter = $this->getHelper('formatter');
        $printer = new Printer($output, $formatter);

        $printer->header(intval(date('Y')));

        $envFile = getcwd() . DIRECTORY_SEPARATOR . '.env';
        if (is_readable($envFile)) {
            $printer->warningLine(".env file already exists");
            $question = new ConfirmationQuestion('Do you want to override it? [<comment>n</comment>] ', false);

            if (!$helper->ask($input, $output, $question)) {
                return Command::SUCCESS;
            }
        }

        $year = strval($input->getOption("year"));
        if (!$year) {
            $question = new Question(sprintf("Which year do you want to solve? [<comment>%s</comment>] ", date("Y")), date("Y"));
            $year = strval($helper->ask($input, $output, $question));
        }

        $session = strval($input->getOption("session"));
        if (!$session) {
            $question = new Question("What is your Advent of Code session cookie value? ", "");
            $session = strval($helper->ask($input, $output, $question));
        }

        if (!$session) {
            $output->writeln("<comment>Session cookie was not set. No problem, you can add it later into .env file.</comment>");
        }

        $saved = @file_put_contents($envFile, sprintf("AOC_SESSION=%s\nAOC_YEAR=%s", $session, $year));

        if ($saved !== false) {
            $output->writeln("<info>.env file created</info> in " . $envFile);
            return Command::SUCCESS;
        } else {
            $output->writeln("<error>.env file cannot be created</error>");
            return Command::FAILURE;
        }
    }
}
