<?php

namespace AdventOfCode;

abstract class Solution
{
    public function __construct(protected int $day)
    {
        $this->input = new Input($day);
    }
}
