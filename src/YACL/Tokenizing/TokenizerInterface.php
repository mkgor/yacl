<?php


namespace YACL\Tokenizing;

/**
 * Interface TokenizerInterface
 *
 * @package YACL
 */
interface TokenizerInterface
{
    /**
     * @param string $data
     *
     * @return array
     */
    public function run(string $data): array;

    /**
     * @return TokenCollection
     */
    public function getTokensCollection(): TokenCollection;

    /**
     * @param TokenCollection $tokensCollection
     */
    public function setTokensCollection(TokenCollection $tokensCollection): void;
}