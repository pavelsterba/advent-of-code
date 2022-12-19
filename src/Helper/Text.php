<?php

declare(strict_types=1);

namespace AdventOfCode\Helper;

class Text implements \Stringable
{
    private $original;

    public function __construct(string $string)
    {
        $this->original = $string;
    }

    public function __toString(): string
    {
        return $this->original;
    }

    public function value()
    {
        return $this->original;
    }
}
