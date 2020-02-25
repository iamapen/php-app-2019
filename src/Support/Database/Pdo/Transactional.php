<?php declare(strict_types=1);

namespace Acme\Support\Database\Pdo;

trait Transactional
{
    /** @var PdoTransactionInterface */
    private $transactional;

    /**
     * @param callable $func 一連のDBトランザクション
     * @return mixed callback次第
     */
    public function transaction(callable $func)
    {
        $result = null;
        try {
            $result = $func();
            $this->transactional->commit();
        } catch (\Throwable $e) {
            $this->transactional->rollback();
        }
        return $result;
    }
}
