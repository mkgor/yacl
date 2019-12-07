<?php

namespace YACL\Tokenizing;

use YACL\Entity\Token;

/**
 * Class Tokenizer
 *
 * @package YACL
 */
class Tokenizer implements TokenizerInterface
{
    /**
     * Core token's constants
     */
    const ARRAY_START_TOKEN_NAME = 'array_start';
    const ARRAY_END_TOKEN_NAME = 'array_end';
    const EQUALITY_TOKEN_NAME = 'equality';
    const STRING_TOKEN_NAME = "string";
    const DATA_ARRAY_TOKEN_NAME = "data_array";

    const ARRAY_START_TOKEN_SIGNATURE = 'are';
    const ARRAY_END_TOKEN_SIGNATURE = 'end';
    const EQUALITY_TOKEN_SIGNATURE = 'is';

    const STRING_TOKEN = '"';
    const ID_TOKEN = 'id';

    /**
     * @var TokenCollection
     */
    private $tokensCollection;

    /**
     * @return TokenCollection
     */
    public function getTokensCollection(): TokenCollection
    {
        return $this->tokensCollection;
    }

    /**
     * @param TokenCollection $tokensCollection
     */
    public function setTokensCollection(TokenCollection $tokensCollection): void
    {
        $this->tokensCollection = $tokensCollection;
    }

    /**
     * Tokenizer constructor.
     *
     * @param TokenCollection $tokensCollection
     */
    public function __construct(TokenCollection $tokensCollection = null)
    {
        $this->setTokensCollection(new TokenCollection($tokensCollection));
    }

    /**
     * @param string $data Raw data to tokenize
     *
     * @return array
     */
    public function run(string $data): array
    {
        /**
         * Dividing the contents of the file into lines
         *
         * @var array $explodedData
         */
        $explodedData = array_map('trim', explode(PHP_EOL, trim($data)));

        /**
         * Deleting extra spaces
         *
         * @var array $data
         */
        $data = array_map(function ($item) {
            return preg_replace('|\s+|', ' ', $item);
        }, $explodedData);

        /**
         * Deleting whitespaces before and after commas
         *
         * @var array $data
         */
        $data = str_replace(', ', ',', str_replace(' ,', ',', $data));

        return $this->handleTokensArray($data);
    }

    /**
     * Returns array of tokens
     *
     * @param array $linesArray
     *
     * @return array
     */
    private function handleTokensArray(array $linesArray)
    {
        $result = [];
        $tokensArray = [];

        /**
         * Going through lines of file to find commented lines and remove it from tokenizer queue
         *
         * @var string $line
         */
        foreach ($linesArray as $line) {
            if (substr($line, 0, 2) == '//') {
                continue;
            } else {
                $tokens = explode(" ", trim($line));

                foreach ($tokens as $token) {
                    if (!empty($token)) {
                        $tokensArray[] = $token;
                    }
                }
            }
        }

        /**
         * Going through tokens array and calling tokens' callback functions for token type recognition
         * If recognition was successful, we put its result into $tmp, then checking - if $tmp is not null -
         * So we found some token which is not ID and putting it into $result array.
         *
         * If we cant recognize type of token - so it is ID. $tmp will be null, so we just putting in $result array as is
         */
        foreach ($tokensArray as $token) {
            foreach ($this->getTokensCollection()->getTokenContainer() as $item) {
                $tmp = null;
                /** @var array $tokenRecognitionResult */
                $tokenRecognitionResult = $item->getTokenRecognitionCallbackResult($token);

                if($tokenRecognitionResult != false) {
                    $tmp = $tokenRecognitionResult;
                    break;
                }
            }

            /**
             * Checking recognition result
             */
            if(isset($tmp) && $tmp != null) {
                $result[] = $tmp;
                continue;
            }

            /**
             * If recognition was unsuccessful, so it is ID and we are putting it into result array with type `id`
             */
            $result[] = [
                'type'    => 'id',
                'content' => $token,
            ];
        }

        return $result;
    }
}