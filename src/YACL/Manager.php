<?php


namespace YACL;


use YACL\Entity\CompilationResult;
use YACL\Tokenizing\Tokenizer;
use YACL\Tokenizing\TokenizerInterface;
use YACL\Transliteration\Transliterator;
use YACL\Transliteration\TransliteratorInterface;

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
     * @var TransliteratorInterface
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
     * @param TokenizerInterface      $tokenizer
     * @param TransliteratorInterface $transliterator
     */
    public function __construct(TokenizerInterface $tokenizer = null, TransliteratorInterface $transliterator = null)
    {
        $this->tokenizer = $tokenizer ?? new Tokenizer();
        $this->compiler = $transliterator ?? new Transliterator();
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     * @codeCoverageIgnore
     */
    public function setPath(string $path)
    {
        $this->path = $path;
    }

    /**
     * @param string $path
     *
     * @return CompilationResult
     * @throws Exceptions\UnknownTokenException
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
                    $result = new CompilationResult(null, $cacheFile['result']);
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
     * @return CompilationResult
     * @throws Exceptions\UnknownTokenException
     */
    private function process($content, $cache = false)
    {
        $tokensArray = $this->tokenizer->run($content);

        $result = $this->compiler->compile($tokensArray, $this->tokenizer->getTokensCollection());

        if($cache) {
            if($result->asArray() != false) {
                $this->generateCacheFile($result->getRaw());
            }
        }

        return $result;
    }

    /**
     * @return bool
     */
    private function cacheEnabled(): bool
    {
        return (defined('YCL_CACHE') && YCL_CACHE == 1);
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