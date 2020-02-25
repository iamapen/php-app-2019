<?php declare(strict_types=1);

namespace Acme\Support\Cache;

use Psr\SimpleCache\CacheInterface;

/**
 * 固定サイズのキャッシュ配列をLRUで入れ替えるキャッシュ
 */
class ArrayLruSimpleCache implements CacheInterface
{

    /** @var int */
    protected $maxSize;

    /** @var mixed[] */
    protected $items = [];

    public function __construct(int $size)
    {
        if ($size <= 0) {
            throw new InvalidArgumentException(sprintf(
                'Size must be a positive integer, "%s" given',
                $size
            ));
        }
        $this->maxSize = $size;
    }

    /**
     * @inheritDoc
     */
    public function get($key, $default = null)
    {
        if (!isset($this->items[$key])) {
            return $default;
        }

        $this->reTouch($key);
        return $this->items[$key];
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param null|int|\DateInterval $ttl 使われない
     * @return bool
     */
    public function set($key, $value, $ttl = null)
    {
        if (array_key_exists($key, $this->items)) {
            // 既に存在する場合は上書き
            $this->items[$key] = $value;
            $this->reTouch($key);
            return true;
        }

        $this->items[$key] = $value;
        if ($this->getSize() > $this->maxSize) {
            // 新規追加で保存数上限を超えている場合、LRU
            reset($this->items);
            unset($this->items[key($this->items)]);
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    public function delete($key)
    {
        unset($this->items[$key]);
        return true;
    }

    /**
     * @inheritDoc
     */
    public function clear()
    {
        $this->items = [];
        return true;
    }

    /**
     * @inheritDoc
     */
    public function getMultiple($keys, $default = null)
    {
        if ($keys instanceof \Traversable) {
            $keys = iterator_to_array($keys, false);
        } elseif (!is_array($keys)) {
            throw new InvalidArgumentException(sprintf(
                'Cache keys must be array or Traversable, "%s" given',
                is_object($keys) ? get_class($keys) : gettype($keys)
            ));
        }

        $result = [];
        foreach ($keys as $key) {
            $result[] = $this->get($key);
        }
        return $result;
    }

    /**
     * @param iterable $values
     * @param null|int|\DateInterval $ttl 使われない
     * @return bool
     * @throws InvalidArgumentException
     */
    public function setMultiple($values, $ttl = null)
    {
        if (!is_array($values) && !$values instanceof \Traversable) {
            throw new InvalidArgumentException(sprintf(
                'Cache values must be array or Traversable, "%s" given',
                is_object($values) ? get_class($values) : gettype($values)
            ));
        }
        foreach ($values as $key => $value) {
            $this->set($key, $value, $ttl);
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteMultiple($keys)
    {
        if ($keys instanceof \Traversable) {
            $keys = iterator_to_array($keys, false);
        } elseif (!is_array($keys)) {
            throw new InvalidArgumentException(sprintf(
                'Cache keys must be array or Traversable, "%s" given',
                is_object($keys) ? get_class($keys) : gettype($keys)
            ));
        }

        foreach ($keys as $key) {
            $this->delete($key);
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    public function has($key)
    {
        return isset($this->items[$key]);
    }

    /**
     * 最終参照にする
     * @param string $key
     */
    protected function reTouch(string $key): void
    {
        $value = $this->items[$key];
        unset($this->items[$key]);
        $this->items[$key] = $value;
    }

    /**
     * キャッシュされている要素数を返す
     * @return int
     */
    public function getSize(): int
    {
        return count($this->items);
    }
}
