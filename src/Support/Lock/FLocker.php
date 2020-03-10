<?php

namespace Acme\Support\Lock;

class FLocker implements ILocker
{
    /** @var resource */
    private $fp;

    private function __construct($fp)
    {
        if (!is_resource($fp)) {
            throw new \InvalidArgumentException(sprintf(
                'fp must be resource, "%s" given', $fp
            ));
        }
        $this->fp = $fp;
    }

    public static function createByFullpath(string $fullpath)
    {
        $fp = fopen($fullpath, 'w+b');
        if ($fp === false) {
            throw new \RuntimeException(sprintf(
                'open failed "%s"', $fullpath
            ));
        }
        return new static($fp);
    }

    public static function createByResource($fp)
    {
        return new static($fp);
    }

    public function getLock(): bool
    {
        $locked = flock($this->fp, LOCK_EX | LOCK_NB);
        return $locked;
    }

    public function getLockAndWait(): bool
    {
        $locked = flock($this->fp, LOCK_EX);
        return $locked;
    }

    public function unLock()
    {
        $unlocked = flock($this->fp, LOCK_UN);
        return $unlocked;
    }

    public function unLockAndClose()
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
