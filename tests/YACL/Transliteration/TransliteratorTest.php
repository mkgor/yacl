<?php

namespace YACL\Transliteration;


use PHPUnit\Framework\TestCase;
use YACL\Manager;

class TransliteratorTest extends TestCase
{
    /**
     * @var Manager
     */
    private $manager;

    public function setUp(): void
    {
        $this->manager = new Manager();
    }

    /**
     * @throws \YACL\Exceptions\UnknownTokenException
     */
    public function testParseError()
    {
        $result = $this->manager->parseYcl(__DIR__ . '/../../test_with_error.ycl')->asObject();

        $this->assertFalse($result);
    }
}
