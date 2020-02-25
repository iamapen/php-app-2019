<?php declare(strict_types=1);

namespace Acme\Support\Database\DoctrineDbal;

/**
 * 接続しないMysql Driver
 */
class NullMysqlDriver implements \Doctrine\DBAL\Driver
{
    public function connect(array $params, $username = null, $password = null, array $driverOptions = []) { }

    public function getDatabasePlatform()
    {
        return new \Doctrine\DBAL\Platforms\MySqlPlatform();
    }

    public function getSchemaManager(\Doctrine\DBAL\Connection $conn) { }

    public function getName() { }

    public function getDatabase(\Doctrine\DBAL\Connection $conn) { }
}
