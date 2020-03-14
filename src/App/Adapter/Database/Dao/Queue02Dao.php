<?php

namespace Acme\App\Adapter\Database\Dao;

use Acme\App\Domain\JobQueue02\Status;
use Acme\Support\Database\QueryBuilder\LimitCondition;

class Queue02Dao extends DaoBase
{
    private $tableName = 'queue02';

    public function findOneByJobId($jobId, $uuid, ?LimitCondition $limit = null): ?array
    {
        if ($limit === null) {
            $limit = LimitCondition::create();
        }

        $params = [
            'job_id' => $jobId, 'locker_uuid1' => $uuid,
        ];
        $sql = $limit->buildSelectPartStatement([
            'id', 'job_id', 'locker_uuid1', 'created_at', 'updated_at',
        ]);
        $sql .= "  FROM {$this->tableName}";
        $sql .= " WHERE job_id = :job_id";
        $sql .= "   AND locker_uuid1 = :locker_uuid1";
        $sql .= $limit->buildLimitPartStatement();
        return $this->prepareAndExecuteOne($sql, $params);
    }

    public function insert($msg)
    {
        $strNow = date('Y-m-d H:i:s');

        $sql = " INSERT INTO {$this->tableName}";
        $sql .= "  (job_id, created_at, updated_at)";
        $sql .= "VALUES (:job_id, :created_at, :updated_at)";
        $params = [
            'job_id' => $msg['job_id'],
            'created_at' => $strNow,
            'updated_at' => $strNow,
        ];
        return $this->prepareAndExecute($sql, $params)->rowCount();
    }

    public function lock($jobId, $uuid): int
    {
        $strNow = date('Y-m-d H:i:s');

        $params = [
            'job_id' => $jobId, 'locker_uuid1' => $uuid,
        ];
        $sql = " UPDATE {$this->tableName}";
        $sql .= "   SET status =" . $this->pdo->quote(Status::RUNNING);
        $sql .= "      , locker_uuid1 = :locker_uuid1";
        $sql .= "      , updated_at = {$this->pdo->quote($strNow)}";
        $sql .= " WHERE job_id = :job_id";
        $sql .= "   AND status = " . $this->pdo->quote(Status::PUBLISHED);
        $sql .= " ORDER BY id";
        $sql .= " LIMIT 1";
        return $this->prepareAndExecute($sql, $params)->rowCount();
    }

    public function deleteById($pkey)
    {
        $params = [
            'id' => $pkey,
        ];
        $sql = " DELETE FROM {$this->tableName}";
        $sql .= " WHERE id = :id";
        return $this->prepareAndExecute($sql, $params)->rowCount();
    }

    public function deleteMarkedById($pkey, $jobId)
    {
        $params = [
            'id' => $pkey, 'job_id' => $jobId,
        ];
        $sql = " DELETE FROM {$this->tableName}";
        $sql .= " WHERE id = :id";
        $sql .= "   AND job_id = :job_id";
        $sql .= "   AND status = " . $this->pdo->quote(Status::RUNNING);
        return $this->prepareAndExecute($sql, $params)->rowCount();
    }

    public function deleteMarkedByJobId($jobId, $uuid)
    {
        $params = [
            'job_id' => $jobId, 'locker_uuid1' => $uuid,
        ];
        $sql = " DELETE FROM {$this->tableName}";
        $sql .= " WHERE job_id = :job_id";
        $sql .= "   AND status = " . $this->pdo->quote(Status::RUNNING);
        $sql .= "   AND locker_uuid1 = :locker_uuid1";
        return $this->prepareAndExecute($sql, $params)->rowCount();
    }
}
