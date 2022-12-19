<?php

declare(strict_types=1);

namespace AdventOfCode\Command;

use AdventOfCode\Utils\Printer;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'about', description: "Advent of Code for PHP")]
class AboutCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var \Symfony\Component\Console\Helper\FormatterHelper $formatter */
        $formatter = $this->getHelper('formatter');
        $printer = new Printer($output, $formatter);
        $commands = [
            "dotenv" => "Generate .env file",
            "input" => "Download input data for puzzle",
            "run" => "Run solution",
        ];

        $printer->header(intval(date('Y')));

        $output->writeln("<fg=yellow>Usage:</>");
        $output->writeln("  command [options] [arguments]");

        $output->writeln("");

        $output->writeln("<fg=yellow>Commands:</>");
        foreach ($commands as $cmd => $description) {
            $output->writeln(sprintf("  <fg=green>%s</>%s", str_pad($cmd, 10), $description));
        }

        return Command::SUCCESS;
    }
}
