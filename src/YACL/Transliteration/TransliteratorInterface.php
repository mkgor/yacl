<?php

namespace YACL\Transliteration;

use YACL\Entity\CompilationResult;
use YACL\Tokenizing\TokenCollection;

interface TransliteratorInterface
{
    /**
     * @param array           $input
     * @param TokenCollection $tokenCollection
     *
     * @return CompilationResult
     */
    public function compile(array $input, TokenCollection $tokenCollection): CompilationResult;
}