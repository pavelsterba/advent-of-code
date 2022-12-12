<?php

declare(strict_types=1);

namespace AdventOfCode\Generator;

use AdventOfCode\Solution;
use Nette\PhpGenerator\ClassType;

class SolutionGenerator
{
    public function generate(): string
    {
        $class = new ClassType("Solution");

        $class->setExtends(Solution::class);

        $class->addMethod("first")->setPublic()->setBody("\$input = \$this->input->load();\n\nthrow new AdventOfCode\\Exception\\NotImplementedException();");
        $class->addMethod("second")->setPublic()->setBody("\$input = \$this->input->load();\n\nthrow new AdventOfCode\\Exception\\NotImplementedException();");

        return strval($class);
    }
}
