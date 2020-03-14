<?php

namespace Acme\App\Adapter\Database\Command;

use Acme\App\Adapter\Database\Dao\Queue02Dao;
use Acme\App\Domain\JobQueue02\JobId;
use Acme\App\Domain\JobQueue02\JobQueue02CommandInterface;
use Acme\Support\Database\QueryBuilder\LimitCondition;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class Queue02Command implements JobQueue02CommandInterface
{
    private $dao;

    public function __construct(Queue02Dao $dao)
    {
        $this->dao = $dao;
    }

    /** @inheritDoc */
    public function publish(JobId $jobId): int
    {
        $this->dao->insert(['job_id' => $jobId]);
        return 1;
    }

    /** @inheritDoc */
    public function publishByString(string $strJobId)
    {
        $this->dao->insert(['job_id' => $strJobId]);
        return 1;
    }

    /** @inheritDoc */
    public function lock(string $jobId): ?UuidInterface
    {
        $uuid = Uuid::uuid1();
        if (1 === $this->dao->lock($jobId, $uuid)) {
            return $uuid;
        }
        return null;
    }

    /** @inheritDoc */
    public function subscribe(JobId $jobId, ?LimitCondition $limit = null): ?array
    {
        return $this->subscribeByString($jobId, $limit);
    }

    /** @inheritDoc */
    public function subscribeByString(string $strJobId, ?LimitCondition $limit = null): ?array
    {
        if (null === $uuid = $this->lock($strJobId)) {
            return null;
        }
        return $this->dao->findOneByJobId($strJobId, $uuid, $limit);
    }

    /** @inheritDoc */
    public function dequeueByMsgId($msgId, $jobId): int
    {
        return $this->dao->deleteMarkedById($msgId, $jobId);
    }

    /** @inheritDoc */
    public function dequeueByString($strJobId, $uuid): int
    {
        return $this->dao->deleteMarkedByJobId($strJobId, $uuid);
    }
}
