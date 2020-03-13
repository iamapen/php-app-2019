<?php

namespace Acme\App\Adapter\Database\Command;

use Acme\App\Adapter\Database\Dao\Queue01Dao;
use Acme\App\Domain\JobQueue\JobQueueCommand;
use Acme\Support\Database\QueryBuilder\LimitCondition;

class Queue01Command implements JobQueueCommand
{
    private $dao;

    public function __construct(Queue01Dao $dao)
    {
        $this->dao = $dao;
    }

    public function publish($jobId): int
    {
        $this->dao->insert(['job_id' => $jobId]);
        return 1;
    }

    public function subscribe(?LimitCondition $limit = null): array
    {
        $this->dao->createNewTable();
        $this->dao->swapTable();
        return $this->dao->findFromRunning($limit);
    }

    public function subscribeDone()
    {
        $this->dao->dropRunning();
    }
}
