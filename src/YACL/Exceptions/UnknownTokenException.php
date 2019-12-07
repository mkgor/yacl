<?php


namespace YACL\Exceptions;


use Throwable;

/**
 * Class UnknownTokenException
 *
 * @package YACL\Exceptions
 */
class UnknownTokenException extends \Exception
{
    /**
     * UnknownTokenException constructor.
     *
     * @param string $token
     */
    public function __construct($token = "")
    {
        parent::__construct("YACL compiler error: Unknown token `" . $token . "`", 500);
    }
}