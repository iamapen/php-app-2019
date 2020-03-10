<?php

namespace Acme\Support\Lock;

class MkdirLocker implements ILocker
{
    /** @var string */
    private $fullpath;
    /** @var bool */
    private $locked = false;

    private function __construct($fullpath)
    {
        $dir = dirname($fullpath);
        if (!file_exists($dir)) {
            throw new \RuntimeException(sprintf(
                'directory does not exists "%s"', $dir
            ));
        }
        if (!is_writable($dir)) {
            throw new \RuntimeException(sprintf(
                'cannot writable directory "%s"', $dir
            ));
        }
        $this->fullpath = $fullpath;
    }

    public static function createByFullpath(string $fullpath)
    {
        return new static($fullpath);
    }

    public function getLock(): bool
    {
        if (file_exists($this->fullpath)) {
            return false;
        }
        $this->locked = mkdir($this->fullpath);
        return $this->locked;
    }

    public function unLock(): bool
    {
        if (!$this->locked) {
            return false;
        }
        if (!file_exists($this->fullpath)) {
            return false;
        }
        return rmdir($this->fullpath);
    }
}
