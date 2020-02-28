<?php

namespace Acme\Test;

abstract class DbBaseTestCase extends \PHPUnit\DbUnit\TestCase
{

    protected function getConnection()
    {
        return new \PHPUnit\DbUnit\Database\DefaultConnection($this->getPdo());
    }

    protected function getDataSet()
    {
        return new \PHPUnit\DbUnit\DataSet\ArrayDataSet([]);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    protected final function getPdoDsn() {
        return sprintf('mysql:host=%s;dbname=%s;charset=utf8', getenv('DB_MASTER_HOST'), getenv('DB_MASTER_DATABASE'));
    }

    protected final function getPdoOpts() {
        return [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
        ];
    }

    /**
     * @return \Acme\Support\Database\Pdo\Pdo
     */
    protected final function getPdo()
    {
        static $pdo;
        if ($pdo !== null) {
            return $pdo;
        }

        $pdoOpts = $this->getPdoOpts();
        $strDsn = $this->getPdoDsn();
        $pdo = new \Acme\Support\Database\Pdo\Pdo($strDsn, getenv('DB_MASTER_USERNAME'), getenv('DB_MASTER_PASSWORD'), $pdoOpts);

        //$pdo->exec('SET SESSION sql_mode="ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION"');
        //$pdo->exec('SET SESSION sql_mode="STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION"');
        // ゼロ日付が入っているから NO_ZERO_IN_DATE, NO_ZERO_DATE は付けられない...
        $pdo->exec('SET SESSION sql_mode="STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION"');

        return $pdo;
    }

    protected function getSetUpOperation()
    {
        return \PHPUnit\DbUnit\Operation\Factory::CLEAN_INSERT();
    }

    protected function getTearDownOperation()
    {
        return \PHPUnit\DbUnit\Operation\Factory::NONE();
    }
}
