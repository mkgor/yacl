<?php

namespace YACL;

use YACL\Entity\CompilationResult;

interface CompilerInterface
{
    public function compile(array $input): CompilationResult;
}