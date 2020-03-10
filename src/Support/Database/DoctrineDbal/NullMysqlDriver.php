<?php declare(strict_types=1);

namespace Acme\Support\Database\DoctrineDbal;

/**
 * 接続しないMysql Driver
 *
 * DBに接続せずに、QueryBuilderを単体で得るために存在
 */
class NullMysqlDriver implements \Doctrine\DBAL\Driver
{
    public function connect(array $params, $username = null, $password = null, array $driverOptions = [])
    {
        // do nothing
    }

    public function getDatabasePlatform()
    {
        return new \Doctrine\DBAL\Platforms\MySqlPlatform();
    }

    public function getSchemaManager(\Doctrine\DBAL\Connection $conn)
    {
        // do nothing
    }

    public function getName()
    {
        // do nothing
    }

    public function getDatabase(\Doctrine\DBAL\Connection $conn)
    {
        // do nothing
    }
}
