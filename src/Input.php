<?php

declare(strict_types=1);

namespace AdventOfCode;

class Input
{
    const INPUT_FILE = 'input.txt';

    private string $inputFile;

    public function __construct(int $day, int $year = null)
    {
        $year = $year ?? date('Y');
        $this->inputFile = getcwd() . DIRECTORY_SEPARATOR . $year . DIRECTORY_SEPARATOR . 'day-' . $day . DIRECTORY_SEPARATOR . self::INPUT_FILE;
    }

    /**
     * @return string|array<string>
     */
    public function load(bool $splitLines = true): string|array
    {
        $data = @file_get_contents($this->inputFile);

        if ($data !== false) {
            $data = trim($data);
        } else {
            $data = "";
        }

        if ($splitLines) {
            $data = explode("\n", $data);
        }

        return $data;
    }
}
