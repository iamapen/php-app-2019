<?php declare(strict_types=1);

namespace Acme\Support\Database\Pdo;

trait Transactional
{
    /** @var PdoTransactionInterface */
    private $transactional;

    /**
     * @param callable $func 一連のDBトランザクション
     * @return mixed callback次第
     * @throws \PDOException
     * @throws \Throwable
     */
    public function transaction(callable $func)
    {
        $result = null;
        try {
            $result = $func();
            $this->transactional->commit();
        } catch (\Throwable $e) {
            $this->transactional->rollback();
            throw $e;
        }
        return $result;
    }
}
