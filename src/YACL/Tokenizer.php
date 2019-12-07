<?php

namespace YACL;

/**
 * Class Tokenizer
 *
 * @package YACL
 */
class Tokenizer implements TokenizerInterface
{
    const ARRAY_START_TOKEN_NAME = 'array_start';
    const ARRAY_END_TOKEN_NAME = 'array_end';
    const EQUALITY_TOKEN_NAME = 'equality';
    const STRING_TOKEN_NAME = "string";
    const DATA_ARRAY_TOKEN_NAME = "data_array";

    const STRING_TOKEN = '"';
    const ID_TOKEN = 'id';

    /**
     * @var array
     */
    public static $tokens = [
        self::ARRAY_START_TOKEN_NAME => "are",
        self::ARRAY_END_TOKEN_NAME   => "end",

        self::EQUALITY_TOKEN_NAME => "is",
    ];

    /**
     * @param string $data Raw data to tokenize
     *
     * @return array
     */
    public function run(string $data): array
    {
        $explodedData = array_map('trim', explode(PHP_EOL, trim($data)));

        $data = array_map(function ($item) {
            return preg_replace('|\s+|', ' ', $item);
        }, $explodedData);

        $data = str_replace(', ', ',', $data);
        $data = str_replace(' ,', ',', $data);

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

        foreach ($tokensArray as $token) {
            $type = "id";

            foreach (self::$tokens as $tokenName => $tokenSignature) {
                if ($token == $tokenSignature) {
                    $type = $tokenName;
                    break;
                }
            }

            if (substr($token, 0, 1) == self::STRING_TOKEN && substr($token, -1, 1) == self::STRING_TOKEN) {
                $token = substr($token, 1, -1);
                $type = 'string';
            }

            if (strpos($token, ',') !== false) {
                $type = 'data_array';
            }

            $result[] = [
                'type'    => $type,
                'content' => $token,
            ];
        }

        return $result;
    }
}