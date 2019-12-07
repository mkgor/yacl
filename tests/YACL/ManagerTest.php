<?php

namespace YACL;

use PHPUnit\Framework\TestCase;

class ManagerTest extends TestCase
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
     * @throws Exceptions\UnknownTokenException
     */
    public function testParseYcl()
    {
        $result = $this->manager->parseYcl(__DIR__ . '/../test.ycl');

        $this->assertIsArray($result->asArray());
        $this->assertIsObject($result->asObject());
    }

    /**
     * @throws Exceptions\UnknownTokenException
     */
    public function testCache()
    {
        define('YCL_CACHE', 1);
        define('YCL_CACHE_DIR', __DIR__ . '/../cache');

        $result = $this->manager->parseYcl(__DIR__ . '/../test.ycl');

        $this->assertIsArray($result->asArray());
        $this->assertIsObject($result->asObject());
        $this->assertFileExists(YCL_CACHE_DIR . '/test.ycl__cache.php');
    }
}
