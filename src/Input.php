<?php

namespace AdventOfCode;

class Input
{
    const INPUT_FILE = 'input.txt';

    private $inputFile;

    public function __construct(int $day, int $year = null)
    {
        $year = $year ?? date('Y');
        $this->inputFile = getcwd() . DIRECTORY_SEPARATOR . $year . DIRECTORY_SEPARATOR . 'day-' . $day . DIRECTORY_SEPARATOR . self::INPUT_FILE;
    }

    public function load(bool $splitLines = true)
    {
        $data = trim(file_get_contents($this->inputFile));

        if ($splitLines) {
            $data = explode("\n", $data);
        }

        return $data;
    }
}
