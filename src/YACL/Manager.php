<?php


namespace YACL;


class Manager
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var TokenizerInterface
     */
    private $tokenizer;

    /**
     * @var CompilerInterface
     */
    private $compiler;

    /**
     * @var string
     */
    private $hash;

    /**
     * @var string
     */
    private $filename;

    /**
     * Manager constructor.
     *
     * @param TokenizerInterface $tokenizer
     * @param CompilerInterface  $compiler
     */
    public function __construct(TokenizerInterface $tokenizer, CompilerInterface $compiler)
    {
        $this->tokenizer = $tokenizer;
        $this->compiler = $compiler;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath(string $path)
    {
        $this->path = $path;
    }

    /**
     * @param string $path
     *
     * @return array|bool
     */
    public function parseYcl(string $path)
    {
        $this->setPath($path);

        $content = file_get_contents($path);

        $result = false;

        if ($this->cacheEnabled()) {
            $this->hash = hash("crc32b", $content);

            $cacheFilepath = YCL_CACHE_DIR . '/' . $this->getFilename() . '__cache.php';

            if (file_exists($cacheFilepath)) {
                $cacheFile = include($cacheFilepath);

                if ($this->hash == $cacheFile['hash']) {
                    $result = $cacheFile['result'];
                } else {
                    $result = $this->process($content, true);
                }
            } else {
                $result = $this->process($content, true);
            }
        } else {
            $result = $this->process($content);
        }

        return $result;
    }

    /**
     * @param string $content
     *
     * @param bool   $cache
     *
     * @return array|bool
     */
    private function process($content, $cache = false)
    {
        $tokensArray = $this->tokenizer->run($content);

        $result = $this->compiler->compile($tokensArray);

        if($cache) {
            if($result->getResult() != false) {
                $this->generateCacheFile($result->getRaw());
            }
        }

        return $result->getResult();
    }

    /**
     * @return bool
     */
    private function cacheEnabled(): bool
    {
        return (YCL_CACHE == 1);
    }

    /**
     * @param string $code
     *
     * @return bool|int
     */
    private function generateCacheFile($code)
    {
        if (!is_dir(YCL_CACHE_DIR)) {
            mkdir(YCL_CACHE_DIR);
        }

        $hash = $this->hash;
        $currentTimestamp = time();

        return file_put_contents(YCL_CACHE_DIR . '/' . $this->getFilename() . '__cache.php', <<<CACHEFILE
<?php
/** Generated at: $currentTimestamp */
return [
    'hash' => '$hash',
    'result' => $code
];      
CACHEFILE
        );
    }

    /**
     * @return mixed
     */
    private function getFilename()
    {
        if (empty($this->filename)) {
            $filenameExploded = explode(DIRECTORY_SEPARATOR, realpath($this->path));

            return array_pop($filenameExploded);
        } else {
            return $this->filename;
        }
    }
}