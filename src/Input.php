<?php

declare(strict_types=1);

namespace AdventOfCode;

class Input
{
    const INPUT_FILE = 'input.txt';
    const INPUT_TEST_FILE = 'input-test.txt';

    private string $inputFile;

    public function __construct(int $day, bool $isTest = false)
    {
        $inputFilename = $isTest ? self::INPUT_TEST_FILE : self::INPUT_FILE;
        $this->inputFile = getcwd() . DIRECTORY_SEPARATOR . date('Y') . DIRECTORY_SEPARATOR . 'day-' . $day . DIRECTORY_SEPARATOR . $inputFilename;
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

    public function getBlocks(): array
    {
        $data = @file_get_contents($this->inputFile);
        $blockId = 0;
        $blocks = [];

        if ($data !== false) {
            foreach (explode("\n\n", trim($data)) as $block) {
                $blocks[$blockId] = [];
                foreach (explode("\n", $block) as $line) {
                    $blocks[$blockId][] = $line;
                }
                $blockId++;
            }
        }

        return $blocks;
    }
}
