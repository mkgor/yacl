<?php

namespace YACL\Entity;

/**
 * Class CompilationResult
 *
 * @package YACL\Entity
 */
class CompilationResult
{
    /**
     * @var string
     */
    private $raw;

    /**
     * @var array|bool
     */
    private $result;

    /**
     * CompilationResult constructor.
     *
     * @param string     $raw
     * @param array|bool $result
     */
    public function __construct(?string $raw, $result)
    {
        $this->raw = $raw;
        $this->result = $result;
    }

    /**
     * @return string
     */
    public function getRaw(): string
    {
        return $this->raw;
    }

    /**
     * @param array|bool $result
     */
    public function setResult($result)
    {
        if(empty($this->result)) {
            $this->result = $result;
        }
    }

    /**
     * Returns result as array
     *
     * @return array|bool
     */
    public function asArray()
    {
        return $this->result;
    }

    /**
     * Returns result as PHP object
     *
     * @return object|bool
     */
    public function asObject()
    {
        return (is_array($this->result)) ? $this->convertArrayToObject($this->result) : $this->result;
    }

    /**
     * Recursively converts array to object
     *
     * @param array $array
     *
     * @return object
     */
    private function convertArrayToObject(array $array)
    {
        foreach($array as $key => $value)
        {
            if(is_array($value))
            {
                $array[$key] = $this->convertArrayToObject($value);
            }
        }

        return (object) $array;
    }
}