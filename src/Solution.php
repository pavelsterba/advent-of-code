<?php

declare(strict_types=1);

namespace AdventOfCode;

abstract class Solution
{
    protected Input $input;

    public function __construct(protected int $day)
    {
        $this->input = new Input($day);
    }
}
