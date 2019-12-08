<?php

namespace YACL\Transliteration;

use YACL\Entity\TransliterationResult;
use YACL\Tokenizing\TokenCollection;

interface TransliteratorInterface
{
    /**
     * @param array           $input
     * @param TokenCollection $tokenCollection
     *
     * @return TransliterationResult
     */
    public function transliterate(array $input, TokenCollection $tokenCollection): TransliterationResult;
}