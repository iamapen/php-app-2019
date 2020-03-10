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
    public static function create(array $selectFields, ?Limit $limit = null): self
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
     * @param string[] $fieldWhiteList field or alias=>field
     * @return string
     */
    public function buildSelectPartStatement($fieldWhiteList): string
    {
        $selectFields = $this->resolveFields($fieldWhiteList);

        $qb = new QueryBuilder(new NullConnection(new NullMysqlDriver()));
        return rtrim($qb->select($selectFields)->getSQL(), ' ') . ' ';
    }

    /**
     * @return string
     */
    public function buildLimitPartStatement(): string
    {
        if (!($this->limit instanceof Limit)) {
            return '';
        }

        $sql = sprintf(' LIMIT %s ', $this->limit->getLimit());
        if (0 !== $offset = $this->limit->getOffset()) {
            $sql .= sprintf('OFFSET %s ', $offset);
        }
        return $sql;
    }

    /**
     * QueryBuilder のSELECTパートを、自身にマージして返す
     * @param QueryBuilder $qb
     * @return LimitCondition
     */
    //public function mergeFromQb(QueryBuilder $qb): LimitCondition
    public function mergeFromQb(QueryBuilder $qb): LimitCondition
    {
        $selectFields = array_values(array_unique(array_merge($this->selectFields, $qb->getQueryPart('select'))));
        return static::create($selectFields, $this->limit);
    }

    /**
     * 自身のSELECTとLIMITを、QueryBuilderにマージして返す
     * @param QueryBuilder $qb
     * @param string[] $selectableFields
     * @return QueryBuilder
     */
    public function mergeToQb(QueryBuilder $qb, $selectableFields = []): QueryBuilder
    {
        $newQb = clone $qb;

        $fields = $this->resolveFields($selectableFields);
        if (!empty($fields)) {
            $newQb->select(array_values(array_unique(array_merge($fields, $newQb->getQueryPart('select')))));
        }

        if (null !== $this->limit) {
            if ($intLimit = $this->limit->getLimit()) {
                $newQb->setMaxResults($intLimit);
            }
            if ($intOffset = $this->limit->getOffset()) {
                $newQb->setFirstResult($intOffset);
            }
        }
        return $newQb;
    }

    /**
     * @param string[] $selectableFields field or alias=>field
     * @return array
     */
    private function resolveFields($selectableFields)
    {
        $resolvedFields = [];

        if (empty($selectableFields)) {
            return [];
        }

        if (empty($this->selectFields)) {
            return $selectableFields;
        }

        foreach ($selectableFields as $alias => $field) {
            if (!is_string($alias)) {
                if (false !== array_search($field, $this->selectFields, true)) {
                    $resolvedFields[] = $field;
                    continue;
                }
            }
            if (false !== array_search($alias, $this->selectFields, true)) {
                $resolvedFields[] = $field;
                continue;
            }
        }
        return $resolvedFields;
    }
}
