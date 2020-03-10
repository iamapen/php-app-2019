<?php

namespace Acme\App\Domain\Models;

use MyCLabs\Enum\Enum;

class Gender extends Enum
{
    /** @var string 男 */
    public const MALE = 'm';
    /** @var string 女 */
    public const FEMALE = 'f';

    protected function __construct($value)
    {
        parent::__construct($value);
    }

    public static function MALE()
    {
        return new static(static::MALE);
    }

    public static function FEMALE()
    {
        return new static(static::FEMALE);
    }
}
