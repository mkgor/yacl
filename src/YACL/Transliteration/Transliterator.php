<?php

namespace YACL\Transliteration;

use YACL\Entity\CompilationResult;
use YACL\Exceptions\UnknownTokenException;
use YACL\Tokenizing\TokenCollection;

/**
 * Class Complier
 *
 * @package YACL
 */
class Transliterator implements TransliteratorInterface
{
    /**
     * @var string Code to compile
     */
    protected $code;

    /**
     * Builds PHP array from array of tokens, which we get from tokenizer
     *
     * @param array           $tokensArray
     *
     * @param TokenCollection $tokenCollection
     *
     * @return mixed
     * @throws UnknownTokenException
     */
    public function compile(array $tokensArray, TokenCollection $tokenCollection): CompilationResult
    {
        $this->code = "[";

        for($i = 0, $count = count($tokensArray); $i < $count; $i++) {
            if($token = $tokenCollection->find($tokensArray[$i]['type'])) {
                $this->code .= $token->getTokenCompilingCallbackResult($tokensArray[$i]['content']);
            } else {
                throw new UnknownTokenException($tokensArray[$i]['type']);
            }
        }

        $this->code .= ']';

        try {
            $result = eval("return " . $this->code . ';');
        } catch (\ParseError $e) {
            $result = false;
        }

        return new CompilationResult($this->code, $result);
    }
}