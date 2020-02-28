<?php declare(strict_types=1);

namespace Acme\Support\Database\QueryBuilder;

/**
 * Limit Offset VO
 */
class Limit
{
    /** @var int */
    private $limit;
    /** @var int */
    private $offset;

    /**
     * Limit constructor.
     * @param string|int $limit
     * @param int|null $offset
     */
    public function __construct($limit, $offset = 0)
    {
        $this->limit = (int)$limit;
        $this->offset = (int)$offset;
    }

    public static function createByOffset($limit, $offset = 0)
    {
        return new static($limit, $offset);
    }

    public static function createByPageNo($perPage, $pageNo = 1)
    {
        $offset = $perPage * ($pageNo - 1);
        return new static($perPage, $offset);
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }
}
