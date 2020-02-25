<?php declare(strict_types=1);

namespace Acme\Support\Database\DoctrineDbal;

use Doctrine\DBAL\Driver;

/**
 * なにもしないConnection
 * DB接続をせずに doctrine/dbal クエリビルダを使いたいため
 */
class NullConnection extends \Doctrine\DBAL\Connection
{
    public function __construct(Driver $driver)
    {
        parent::__construct([], $driver);
    }
}
