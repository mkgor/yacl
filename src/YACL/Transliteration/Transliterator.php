<?php

namespace YACL\Transliteration;

use YACL\Entity\TransliterationResult;
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
     * @param array           $input
     *
     * @param TokenCollection $tokenCollection
     *
     * @return mixed
     * @throws UnknownTokenException
     */
    public function transliterate(array $input, TokenCollection $tokenCollection): TransliterationResult
    {
        $this->code = "[";

        for($i = 0, $count = count($input); $i < $count; $i++) {
            if($token = $tokenCollection->find($input[$i]['type'])) {
                $this->code .= $token->getTokenCompilingCallbackResult($input[$i]['content']);
            } else {
                throw new UnknownTokenException($input[$i]['type']);
            }
        }

        $this->code .= ']';

        try {
            $result = eval("return " . $this->code . ';');
        } catch (\ParseError $e) {
            $result = false;
        }

        return new TransliterationResult($this->code, $result);
    }
}