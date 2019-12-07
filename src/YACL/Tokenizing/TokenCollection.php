<?php

namespace YACL\Tokenizing;

use YACL\Entity\Token;

/**
 * Class TokenCollection
 *
 * @package YACL
 */
class TokenCollection
{
    /**
     * @var Token[]
     */
    private $tokenContainer;

    /**
     * @return Token[]
     */
    public function getTokenContainer(): array
    {
        return $this->tokenContainer;
    }

    /**
     * @param Token[] $tokenContainer
     */
    public function setTokenContainer(array $tokenContainer): void
    {
        $this->tokenContainer = $tokenContainer;
    }

    /**
     * TokenCollection constructor.
     *
     * @param TokenCollection|null $tokenCollection
     */
    public function __construct(TokenCollection $tokenCollection = null)
    {
        $this->initializeCoreTokens();

        if ($tokenCollection != null) {
            array_merge($tokenCollection->getTokenContainer(), $this->tokenContainer);
        }
    }

    /**
     * @param Token $token
     */
    public function push(Token $token)
    {
        $this->tokenContainer[] = $token;
    }

    /**
     * @param string $name
     *
     * @return Token|bool
     */
    public function find(string $name)
    {
        foreach ($this->tokenContainer as $token) {
            if ($token->getTokenName() == $name) {
                return $token;
            }
        }

        return false;
    }

    /**
     * Adding core tokens to token collection.
     */
    private function initializeCoreTokens()
    {
        $this->push(new Token(Tokenizer::ARRAY_START_TOKEN_NAME,
            function ($token) {
                return ($token == Tokenizer::ARRAY_START_TOKEN_SIGNATURE) ? [
                    'type'    => Tokenizer::ARRAY_START_TOKEN_NAME,
                    'content' => $token,
                ] : false;
            },

            function () {
                return " => [";
            }));

        $this->push(new Token(Tokenizer::ARRAY_END_TOKEN_NAME,
            function ($token) {
                return ($token == Tokenizer::ARRAY_END_TOKEN_SIGNATURE) ? [
                    'type'    => Tokenizer::ARRAY_END_TOKEN_NAME,
                    'content' => $token,
                ] : false;
            },

            function () {
                return "],";
            }));

        $this->push(new Token(Tokenizer::EQUALITY_TOKEN_NAME,
            function ($token) {
                return ($token == Tokenizer::EQUALITY_TOKEN_SIGNATURE) ? [
                    'type'    => Tokenizer::EQUALITY_TOKEN_NAME,
                    'content' => $token,
                ] : false;
            },

            function () {
                return " => ";
            }));

        $this->push(new Token(Tokenizer::STRING_TOKEN_NAME,
            function ($token) {
                return (substr($token, 0, 1) == Tokenizer::STRING_TOKEN && substr($token, -1, 1) == Tokenizer::STRING_TOKEN) ? [
                    'type'    => Tokenizer::STRING_TOKEN_NAME,
                    'content' => substr($token, 1, -1),
                ] : false;
            },

            function ($content) {
                return Tokenizer::STRING_TOKEN . $content . Tokenizer::STRING_TOKEN . ',';
            }));

        $this->push(new Token(Tokenizer::DATA_ARRAY_TOKEN_NAME,
            function ($token) {
                return (strpos($token, ',') !== false) ? [
                    'type'    => Tokenizer::DATA_ARRAY_TOKEN_NAME,
                    'content' => $token,
                ] : false;
            },

            function ($content) {
                return sprintf("%s,", $content);
            }));

        $this->push(new Token(Tokenizer::ID_TOKEN, function () {
            return false;
        }, function ($content) {
            return sprintf("'%s'", $content);
        }));
    }
}