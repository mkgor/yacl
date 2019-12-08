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
}