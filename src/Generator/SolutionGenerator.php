<?php

namespace AdventOfCode\Generator;

use AdventOfCode\Solution;
use Nette\PhpGenerator\ClassType;

class SolutionGenerator
{
    private $day;

    public function __construct(int $day)
    {
        $this->day = $day;
    }

    public function generate()
    {
        $class = new ClassType("Solution");

        $class->setExtends(Solution::class);

        $class->addMethod("first")->setPublic()->setBody("\$input = \$this->input->load();\n\nthrow new AdventOfCode\\Exception\\NotImplementedException();");
        $class->addMethod("second")->setPublic()->setBody("\$input = \$this->input->load();\n\nthrow new AdventOfCode\\Exception\\NotImplementedException();");

        return $class;
    }
}
