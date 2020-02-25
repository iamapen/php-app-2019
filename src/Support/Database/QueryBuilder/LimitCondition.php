<?php declare(strict_types=1);

namespace Acme\Support\Database\QueryBuilder;

use Acme\Support\Database\DoctrineDbal\NullConnection;
use Acme\Support\Database\DoctrineDbal\NullMysqlDriver;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * SELECT, LIMIT/OFFSET VO
 */
class LimitCondition
{
    /** @var string[] */
    private $selectFields = [];

    /** @var Limit */
    private $limit;

    private function __construct(array $select, ?Limit $limit = null)
    {
        $this->selectFields = $select;
        $this->limit = $limit;
    }

    /**
     * @param array $selectFields
     * @param Limit|null $limit
     * @return static
     */
    public static function createByArray(array $selectFields, ?Limit $limit = null): self
    {
        return new static($selectFields, $limit);
    }

    //public static function createByDoctrineQb(QueryBuilder $qbSelect, ?Limit $limit = null): self
    //{
    //    $arrSelect = $qbSelect->getQueryPart('select');
    //    return new static($arrSelect, $limit);
    //}

    /**
     * @return string[]
     */
    public function getSelectFields(): array
    {
        return $this->selectFields;
    }

    /**
     * @return Limit|null
     */
    public function getLimit(): ?Limit
    {
        if ($this->limit === null) {
            return null;
        }
        return clone $this->limit;
    }

    /**
     * カラム指定がない場合は空文字を返す
     * @param string[] $fieldWhiteList field or alias=>field
     * @return string
     */
    public function buildSelectPartStatement($fieldWhiteList): string
    {
        if (empty($this->selectFields)) {
            return '';
        }

        $selectFields = [];
        foreach ($fieldWhiteList as $alias => $field) {
            if (!is_string($alias)) {
                if (false !== array_search($field, $this->selectFields, true)) {
                    $selectFields[] = $field;
                    continue;
                }
            }
            if (false !== array_search($alias, $this->selectFields, true)) {
                $selectFields[] = $field;
                continue;
            }
        }

        if (empty($selectFields)) {
            return '';
        }

        $qb = new QueryBuilder(new NullConnection(new NullMysqlDriver()));
        return $qb->select($selectFields)->getSQL() . ' ';
    }

    /**
     * @return string
     */
    public function buildLimitPartStatement(): string
    {
        if (!($this->limit instanceof Limit)) {
            return '';
        }

        $sql = sprintf('LIMIT %s ', $this->limit->getLimit());
        if (0 !== $offset = $this->limit->getOffset()) {
            $sql .= sprintf('OFFSET %s ', $offset);
        }
        return $sql;
    }

    //public function merge(QueryBuilder $qb): QueryBuilder
    //{
    //    if (!empty($this->select)) {
    //        $qb->select($this->select);
    //    }
    //    if (null !== $this->limit) {
    //        if ($intLimit = $this->limit->getLimit()) {
    //            $qb->setMaxResults($intLimit);
    //        }
    //        if ($intOffset = $this->limit->getOffset()) {
    //            $qb->setFirstResult($intOffset);
    //        }
    //    }
    //    return $qb;
    //}
}
