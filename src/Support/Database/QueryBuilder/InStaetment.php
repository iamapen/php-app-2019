<?php declare(strict_types=1);

namespace Acme\Support\Database\QueryBuilder;

/**
 * IN句を構築する
 */
class InStaetment
{

    /** @var string */
    private $fieldName;
    /** @var string[] */

    private $sources = [];
    private $parameters = [];

    public function __construct(string $fieldName, array $values)
    {
        $this->fieldName = $fieldName;
        $this->sources = $values;
    }

    public function buildStatement(): string
    {
        if (empty($this->sources)) {
            return '';
        }

        $sql = 'IN(';
        foreach ($this->sources as $i => $v) {
            // statement
            $holderName = sprintf('%s_%s', $this->fieldName, $i + 1);
            $sql .= ":{$holderName}" . ',';
            // parameters
            unset($this->sources[$i]);
            $this->parameters[$holderName] = $v;
        }
        $sql = rtrim($sql, ',');
        $sql .= ') ';

        return $sql;
    }

    /**
     * @return string[]
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }
}
