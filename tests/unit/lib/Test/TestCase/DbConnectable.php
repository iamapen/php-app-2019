<?php declare(strict_types=1);

namespace Acme\Test\TestCase;

trait DbConnectable
{
    protected final function getPdoDsn(): string
    {
        return sprintf(
            'mysql:host=%s;dbname=%s;charset=utf8mb4',
            getenv('DB_MASTER_HOST'),
            getenv('DB_MASTER_DATABASE')
        );
    }

    protected final function getPdoOpts(): array
    {
        return [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
        ];
    }

    /**
     * @return \Acme\Support\Database\Pdo\PdoInterface|\PDO
     */
    protected final function getPdo(): \Acme\Support\Database\Pdo\PdoInterface
    {
        static $pdo;
        if ($pdo !== null) {
            return $pdo;
        }

        $pdoOpts = $this->getPdoOpts();
        $strDsn = $this->getPdoDsn();
        $pdo = new \Acme\Support\Database\Pdo\Pdo(
            $strDsn, getenv('DB_MASTER_USERNAME'), getenv('DB_MASTER_PASSWORD'),
            $pdoOpts
        );

        //$pdo->exec('SET SESSION sql_mode="ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION"');
        $pdo->exec('SET SESSION sql_mode="STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION"');

        return $pdo;
    }
}
