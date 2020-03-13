<?php

namespace Acme\App\Adapter\Database\Dao;

use Acme\Support\Database\QueryBuilder\LimitCondition;

class Queue01Dao extends DaoBase
{
    private $tableName = 'queue01';

    private function resolveRunningTableName()
    {
        return $this->tableName . '_running';
    }

    private function resolveNewTableName()
    {
        return $this->tableName . '_new';
    }

    public function insert($msg)
    {
        $sql = " INSERT INTO {$this->tableName}";
        $sql .= "  (job_id, created_at)";
        $sql .= "VALUES (:job_id, :created_at)";
        $params = [
            'job_id' => $msg['job_id'],
            'created_at' => date('Y-m-d H:i:s'),
        ];
        return $this->prepareAndExecute($sql, $params)->rowCount();
    }

    public function createNewTable()
    {
        $sql = "CREATE TABLE IF NOT EXISTS";
        $sql .= " {$this->resolveNewTableName()} LIKE {$this->tableName}";
        $this->exec($sql);
    }

    public function swapTable()
    {
        $sql = 'RENAME TABLE';
        $sql .= "  {$this->tableName} TO {$this->resolveRunningTableName()}";
        $sql .= " ,{$this->resolveNewTableName()} TO {$this->tableName}";
        return $this->exec($sql);
    }

    public function findFromRunning(?LimitCondition $limit = null): array
    {
        if ($limit === null) {
            $limit = LimitCondition::create();
        }

        $sql = $limit->buildSelectPartStatement(['id', 'job_id', 'created_at']);
        $sql .= "  FROM {$this->resolveRunningTableName()}";
        $sql .= " ORDER BY id ";
        $sql .= $limit->buildLimitPartStatement();
        return $this->prepareAndExecute($sql, [])->fetchAll();
    }

    public function dropRunning()
    {
        $sql = "DROP TABLE {$this->resolveRunningTableName()}";
        return $this->exec($sql);
    }
}
