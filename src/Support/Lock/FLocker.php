<?php

namespace Acme\Support\Lock;

class FLocker implements ILocker
{
    /** @var resource */
    private $fp;

    /** @var string */
    private $fileFullpath;

    private function __construct($fp = null)
    {
        $this->fp = $fp;
    }

    public static function ofFullpath(string $fullpath): self
    {
        $new = new static();
        $new->fileFullpath = $fullpath;
        return $new;
    }

    public static function ofResource($fp)
    {
        if (!is_resource($fp)) {
            throw new \InvalidArgumentException(sprintf(
                'fp must be resource, "%s" given', $fp
            ));
        }
        return new static($fp);
    }

    private function open(): bool
    {
        if (is_resource($this->fp)) {
            return true;
        }

        if (file_exists($this->fileFullpath)) {
            if (!is_file($this->fileFullpath)) {
                throw new \RuntimeException(sprintf(
                    'open failed "%s"', $this->fileFullpath
                ));
            }
        }

        $fp = @fopen($this->fileFullpath, 'w+b');
        if ($fp === false) {
            throw new \RuntimeException(sprintf(
                'open failed "%s"', $this->fileFullpath
            ));
        }
        $this->fp = $fp;
        return true;
    }

    public function getLock(): bool
    {
        $this->open();
        $locked = flock($this->fp, LOCK_EX | LOCK_NB);
        return $locked;
    }

    public function getLockAndWait(): bool
    {
        $locked = flock($this->fp, LOCK_EX);
        return $locked;
    }

    public function unLock(): bool
    {
        if (!is_resource($this->fp)) {
            return false;
        }

        $unlocked = flock($this->fp, LOCK_UN);
        return $unlocked;
    }

    public function unLockAndClose(): bool
    {
        $unlocked = $this->unLock();
        $this->close();
        return $unlocked;
    }

    public function close(): bool
    {
        return fclose($this->fp);
    }
}
