<?php declare(strict_types=1);

namespace Acme\Support\Database\Pdo;

interface PdoTransactionInterface
{

    /**
     * @return bool
     */
    public function inTransaction();

    /**
     * @return bool
     */
    public function beginTransaction();

    /**
     * @return bool
     */
    public function commit();

    /**
     * @return bool
     */
    public function rollback();
}
