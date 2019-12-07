<?php

namespace YACL\Entity;

/**
 * Class Token
 *
 * @package YACL\Entity
 *
 * @method tokenRecognitionCallback(array $args)
 * @method tokenCompilingCallback(array $args)
 */
class Token
{
    /**
     * @var string
     */
    private $tokenName;

    /**
     * @var callable
     */
    private $tokenRecognitionCallback;

    /**
     * @var callable
     */
    private $tokenCompilingCallback;

    /**
     * Token constructor.
     *
     * @param string   $tokenName
     * @param callable $tokenRecognitionCallback
     * @param callable $tokenCompilingCallback
     */
    public function __construct(string $tokenName, callable $tokenRecognitionCallback, callable $tokenCompilingCallback)
    {
        $this->setTokenName($tokenName);
        $this->setTokenRecognitionCallback($tokenRecognitionCallback);
        $this->setTokenCompilingCallback($tokenCompilingCallback);
    }

    /**
     * @return string
     */
    public function getTokenName(): string
    {
        return $this->tokenName;
    }

    /**
     * @param string $tokenName
     */
    public function setTokenName(string $tokenName): void
    {
        $this->tokenName = $tokenName;
    }

    /**
     * @param array $args
     *
     * @return bool|array
     */
    public function getTokenRecognitionCallbackResult(...$args)
    {
        return call_user_func_array($this->tokenRecognitionCallback, $args);
    }

    /**
     * @param callable $tokenRecognitionCallback
     */
    public function setTokenRecognitionCallback(callable $tokenRecognitionCallback): void
    {
        $this->tokenRecognitionCallback = $tokenRecognitionCallback;
    }

    /**
     * @param array $args
     *
     * @return bool|array
     */
    public function getTokenCompilingCallbackResult(...$args)
    {
        return call_user_func_array($this->tokenCompilingCallback, $args);
    }

    /**
     * @param callable $tokenCompilingCallback
     */
    public function setTokenCompilingCallback(callable $tokenCompilingCallback): void
    {
        $this->tokenCompilingCallback = $tokenCompilingCallback;
    }
}