<?php

declare(strict_types=1);

namespace AdventOfCode\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'about', description: "Advent of Code for PHP")]
class AboutCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $commands = [
            "dotenv" => "Generate .env file",
            "input" => "Download input data for puzzle",
            "run" => "Run solution",
        ];

        $logo = [
            '  __   ____  _  _  ____  __ _  ____     __  ____     ___  __  ____  ____ ',
            ' / _\ (    \/ )( \(  __)(  ( \(_  _)   /  \(  __)   / __)/  \(    \(  __)',
            '/    \ ) D (\ \/ / ) _) /    /  )(    (  O )) _)   ( (__(  O )) D ( ) _) ',
            '\_/\_/(____/ \__/ (____)\_)__) (__)    \__/(__)     \___)\__/(____/(____)            ',
        ];

        foreach ($logo as $line) {
            $output->writeln(sprintf("<fg=green>%s</fg=green>", $line));
        }

        $year = '$year = ' . date('Y') . ';';
        $output->writeln(sprintf('<fg=yellow>%s</>', str_pad($year, strlen($logo[0]), " ", STR_PAD_LEFT)));

        $output->writeln("");

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
