<?php

namespace Acme\App\Domain\JobQueue02;

use MyCLabs\Enum\Enum;

class JobId extends Enum
{
    public const JOB02 = 2;
    public const JOB03 = 3;

    public static function JOB02()
    {
        return new static(static::JOB02);
    }

    public static function JOB03()
    {
        return new static(static::JOB03);
    }
}
