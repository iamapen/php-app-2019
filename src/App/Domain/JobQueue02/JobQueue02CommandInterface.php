<?php declare(strict_types=1);

namespace Acme\App\Domain\JobQueue02;

use Acme\Support\Database\QueryBuilder\LimitCondition;
use Ramsey\Uuid\UuidInterface;

interface JobQueue02CommandInterface
{
    /**
     * @param JobId $jobId
     * @return int
     */
    public function publish(JobId $jobId): int;

    /**
     * @param string $strJobId
     * @return mixed
     */
    public function publishByString(string $strJobId);

    /**
     * @param string $jobId
     * @return UuidInterface|null ロックできた場合はuuid
     */
    public function lock(string $jobId): ?UuidInterface;

    /**
     * @param JobId $jobId
     * @param LimitCondition|null $limit
     * @return array|null
     */
    public function subscribe(JobId $jobId, ?LimitCondition $limit = null): ?array;

    /**
     * @param string $strJobId
     * @param LimitCondition|null $limit
     * @return array|null
     */
    public function subscribeByString(string $strJobId, ?LimitCondition $limit = null): ?array;

    /**
     * @param $msgId
     * @param $jobId
     * @return int
     */
    public function dequeueByMsgId($msgId, $jobId): int;

    /**
     * @param $strJobId
     * @param $uuid
     * @return int
     */
    public function dequeueByString($strJobId, $uuid): int;
}
