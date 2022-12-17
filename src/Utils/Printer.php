<?php

declare(strict_types=1);

namespace AdventOfCode\Utils;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\FormatterHelper;

class Printer
{
    private $logo = [
        '  __   ____  _  _  ____  __ _  ____     __  ____     ___  __  ____  ____ ',
        ' / _\ (    \/ )( \(  __)(  ( \(_  _)   /  \(  __)   / __)/  \(    \(  __)',
        '/    \ ) D (\ \/ / ) _) /    /  )(    (  O )) _)   ( (__(  O )) D ( ) _) ',
        '\_/\_/(____/ \__/ (____)\_)__) (__)    \__/(__)     \___)\__/(____/(____)            ',
    ];

    public function __construct(private OutputInterface $output, private FormatterHelper $formatter)
    {
    }

    public function logo(): void
    {
        foreach ($this->logo as $line) {
            $this->output->writeln(sprintf("<fg=green>%s</fg=green>", $line));
        }
    }

    public function getLogoWidth(): int
    {
        return strlen($this->logo[0]);
    }

    public function justify(string $left, string $right, int $lineChars, string $style = null): void
    {
        $spacesCount = $lineChars - strlen($left) - strlen($right);

        if ($style) {
            $this->output->writeln(sprintf("<%s>%s%s%s</>", $style, $left, str_repeat(" ", $spacesCount), $right));
        } else {
            $this->output->writeln(sprintf("%s%s%s", $left, str_repeat(" ", $spacesCount), $right));
        }
    }

    public function right(string $message, int $lineChars, string $style = null): void
    {
        if ($style) {
            $this->output->writeln(sprintf('<%s>%s</>', $style, str_pad($message, $lineChars, " ", STR_PAD_LEFT)));
        } else {
            $this->output->writeln(sprintf('%s', str_pad($message, $lineChars, " ", STR_PAD_LEFT)));
        }
    }

    public function error(string|array $messages): void
    {
        $formattedBlock = $this->formatter->formatBlock($messages, 'bg=red', true);
        $this->output->writeln("");
        $this->output->writeln($formattedBlock);
    }

    public function success(string|array $messages): void
    {
        $formattedBlock = $this->formatter->formatBlock($messages, 'bg=green', true);
        $this->output->writeln("");
        $this->output->writeln($formattedBlock);
    }

    public function warningLine(string $message, string $label = "!"): void
    {
        $this->output->writeln(sprintf("<bg=yellow>[%s]</> %s", $label, $message));
    }

    public function link(string $url, string $title = null)
    {
        $this->output->writeln(sprintf("<href=%s>%s</>", $url, $title ? $title : $url));
    }
}
