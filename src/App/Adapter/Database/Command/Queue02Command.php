<?php

namespace Acme\App\Adapter\Database\Command;

use Acme\App\Adapter\Database\Dao\Queue02Dao;
use Acme\App\Domain\JobQueue02\JobId;
use Acme\Support\Database\QueryBuilder\LimitCondition;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class Queue02Command
{
    private $dao;

    public function __construct(Queue02Dao $dao)
    {
        $this->dao = $dao;
    }

    public function publish(JobId $jobId): int
    {
        $this->dao->insert(['job_id' => $jobId]);
        return 1;
    }

    public function publishByString(string $strJobId)
    {
        $this->dao->insert(['job_id' => $strJobId]);
        return 1;
    }

    /**
     * @param string $jobId
     * @return UuidInterface|null ロックできた場合はuuid
     */
    public function lock(string $jobId): ?UuidInterface
    {
        $uuid = Uuid::uuid1();
        if (1 === $this->dao->lock($jobId, $uuid)) {
            return $uuid;
        }
        return null;
    }

    /**
     * @param JobId $jobId
     * @param LimitCondition|null $limit
     * @return array|null
     */
    public function subscribe(JobId $jobId, ?LimitCondition $limit = null): ?array
    {
        return $this->subscribeByString($jobId, $limit);
    }

    public function subscribeByString(string $strJobId, ?LimitCondition $limit = null): ?array
    {
        if (null === $uuid = $this->lock($strJobId)) {
            return null;
        }
        return $this->dao->findOneByJobId($strJobId, $uuid, $limit);
    }

    public function dequeueByMsgId($msgId, $jobId): int
    {
        return $this->dao->deleteMarkedById($msgId, $jobId);
    }

    public function dequeueByString($strJobId, $uuid): int
    {
        return $this->dao->deleteMarkedByJobId($strJobId, $uuid);
    }
}
