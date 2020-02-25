<?php declare(strict_types=1);

namespace Acme\Support\Database\Pdo;

interface PdoTransactionInterface
{

    public function inTransaction();

    public function beginTransaction();

    public function commit();

    public function rollback();
}
