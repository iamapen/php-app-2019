<?php

namespace Acme\App\Domain\JobQueue;

use Acme\Support\Database\QueryBuilder\LimitCondition;

interface JobQueueCommand
{
    public function subscribe(?LimitCondition $limit = null): array;
}
