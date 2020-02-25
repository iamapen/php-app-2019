<?php

namespace Acme\Support\Database\DoctrineDbal;

use Doctrine\DBAL\Platforms\MySqlPlatform;
use PHPUnit\Framework\TestCase;

class NullMysqlDriverTest extends TestCase
{

    public function testGetDatabasePlatform()
    {
        $sut = new NullMysqlDriver();
        $this->assertSame(MySqlPlatform::class, get_class($sut->getDatabasePlatform()));
    }
}
