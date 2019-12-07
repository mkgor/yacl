<?php


namespace YACL;

use YACL\Entity\CompilationResult;
use YACL\Exceptions\UnknownTokenException;

/**
 * Class Complier
 *
 * @package YACL
 */
class Complier implements CompilerInterface
{
    /**
     * @var string Code to compile
     */
    protected $code;

    /**
     * @param array $tokensArray
     *
     * @return mixed
     * @throws UnknownTokenException
     */
    public function compile(array $tokensArray): CompilationResult
    {

        $this->code = "[";

        for($i = 0, $count = count($tokensArray); $i < $count; $i++) {
            switch ($tokensArray[$i]['type']) {
                case Tokenizer::ID_TOKEN:
                    $this->code .= "'" . $tokensArray[$i]['content'] . "'";
                    break;

                case Tokenizer::ARRAY_START_TOKEN_NAME:
                    $this->code .= " => [";
                    break;

                case Tokenizer::ARRAY_END_TOKEN_NAME:
                    $this->code .= "],";
                    break;

                case Tokenizer::EQUALITY_TOKEN_NAME:
                    $this->code .= " => ";
                    break;

                case Tokenizer::STRING_TOKEN_NAME:
                    $this->code .= Tokenizer::STRING_TOKEN . $tokensArray[$i]['content'] . Tokenizer::STRING_TOKEN . ',';
                    break;

                case Tokenizer::DATA_ARRAY_TOKEN_NAME:
                    $this->code .= $tokensArray[$i]['content'] . ',';
                    break;

                default:
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