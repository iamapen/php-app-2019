<?php

namespace Acme\App\Adapter\Database\Pdo;

use Acme\Support\Database\Pdo\Pdo;
use Acme\Support\Database\Pdo\ReconnectablePdo;

class AppPdo extends Pdo
{
    public function __construct($dsn, $username = null, $passwd = null, array $options = [])
    {
        $pdoOpts = [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
        ];
        foreach ($options as $key => $val) {
            $pdoOpts[$key] = $val;
        }

        parent::__construct($dsn, $username, $passwd, $pdoOpts);
        $this->setSqlMode();
    }

    public static function reconnectable($dsn, $username = null, $passwd = null, $options = null): ReconnectablePdo
    {
        return ReconnectablePdo::createByClassName(
            static::class, $options,
            $dsn, $username, $passwd, $options
        );
    }

    private function setSqlMode()
    {
        $sqlmode = implode(',', [
            'ONLY_FULL_GROUP_BY',
            'STRICT_TRANS_TABLES', 'STRICT_ALL_TABLES',
            'NO_ZERO_IN_DATE', 'NO_ZERO_DATE',
            'ERROR_FOR_DIVISION_BY_ZERO',
            'NO_AUTO_CREATE_USER',
            'NO_ENGINE_SUBSTITUTION',
        ]);
        $this->exec("SET SESSION sql_mode = '{$sqlmode}'");
    }
}
