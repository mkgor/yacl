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
     * @param string $raw
     * @param array|bool  $result
     */
    public function __construct(string $raw, $result)
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
     * @param string $raw
     */
    public function setRaw(string $raw)
    {
        $this->raw = $raw;
    }

    /**
     * @return array|bool
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param array|bool $result
     */
    public function setResult($result)
    {
        $this->result = $result;
    }


}