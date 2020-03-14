<?php

namespace Acme\App\Domain\JobQueue02;

use MyCLabs\Enum\Enum;

class Status extends Enum
{
    /** @var string 初期状態 */
    public const PUBLISHED = '0';
    /** @var string 実行中 */
    public const RUNNING = '1';
}
