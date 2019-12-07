<?php
define('YCL_CACHE', 1);
define('YCL_CACHE_DIR', __DIR__ . '/cache');

$mark = microtime(true);
require_once "../vendor/autoload.php";

$memory = memory_get_usage();

$manager = new \YACL\Manager(new \YACL\Tokenizer(), new \YACL\Complier());

echo json_encode($manager->parseYcl(__DIR__ . '/test.ycl'));



/** BENCHMARK
 * 2000 Lines
 *
 * Time without cache: 0.0059378147125244 s
 * Memory usage without cache: 64.1953125 KB
 *
 * Time with cache: 0.0029101371765137 s
 * Memory usage with cache: 33.0703125 KB
 *
 */

echo microtime(true) - $mark;

echo '<br>';

$usage = (memory_get_usage() - $memory)/1024;

echo $usage . ' KB';