<?php declare(strict_types=1);

namespace Acme\Support\Database\Pdo;

class NoopPdo implements PdoInterface
{

    public function prepare($statement)
    {
        return new NoopPdoStatement();
    }

    public function exec(string $statement)
    {
        return 0;
    }

    public function query()
    {
        return new NoopPdoStatement();
    }

    public function quote($string, $parameter_type = null)
    {
        return $string;
    }

    public function lastInsertId($seqname = null)
    {
        return '1';
    }

    public function errorCode()
    {
        return null;
    }

    public function errorInfo()
    {
        return [];
    }

    public function setAttribute($attribute, $value)
    {
        return true;
    }

    public function getAttribute($attribute)
    {
        return \PDO::ERRMODE_EXCEPTION;
    }

    public static function getAvailableDrivers()
    {
        return [];
    }

    public function inTransaction()
    {
        return true;
    }

    public function beginTransaction()
    {
        return true;
    }

    public function commit()
    {
        return true;
    }

    public function rollback()
    {
        return true;
    }
}
