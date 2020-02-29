<?php declare(strict_types=1);

namespace Acme\Support\Database\Pdo;

class PdoStaetment extends \PDOStatement implements PdoStatementInterface
{
    private function __construct()
    {
        // do nothing
        // コンストラクタが呼べてはならない
    }
}
