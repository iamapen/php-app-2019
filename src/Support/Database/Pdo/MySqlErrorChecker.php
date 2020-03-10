<?php declare(strict_types=1);

namespace Acme\Support\Database\Pdo;

class MySqlErrorChecker
{
    /**
     * サーバサイドタイムアウト(MySQL server has gone away) の例外かどうかを返す
     * @param \PDOException $e
     * @return bool
     */
    public static function isTimeOutException(\PDOException $e): bool
    {
        if ($e->getCode() !== 'HY000') {
            return false;
        }
        if ($e->errorInfo[0] !== 'HY000') {
            return false;
        }
        if ($e->errorInfo[1] !== 2006) {
            return false;
        }
        //if ($e->errorInfo[2] !== 'MySQL server has gone away') {
        //    return false;
        //}
        return true;
    }
}
