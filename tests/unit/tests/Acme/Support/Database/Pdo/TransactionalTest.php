<?php

namespace Acme\Support\Database\Pdo;

use Acme\Test\Fake\Support\Database\Pdo\FakePdo;
use Acme\Test\Fake\Support\Database\Pdo\FakeTransactionalService;
use PHPUnit\Framework\TestCase;

class TransactionalTest extends TestCase
{

    function test_transaction_commit()
    {
        $dbMaster = new FakePdo();
        $sut = new FakeTransactionalService($dbMaster);
        $sut->transaction(function () use ($dbMaster) {
            $dbMaster->exec("INSERT INTO users(name) VALUES('arare')");
        });

        $this->assertSame('commit', end($dbMaster->arrMsg));
    }

    function test_transaction_rollback()
    {
        $dbMaster = new FakePdo();
        $sut = new FakeTransactionalService($dbMaster);

        $e = null;
        try {
            $sut->transaction(function () use ($dbMaster) {
                $dbMaster->exec("INSERT INTO users(name) VALUES('arare')");
                throw new \RuntimeException('test');
            });
        } catch (\Throwable $e) {
        }

        $this->assertSame('rollback', end($dbMaster->arrMsg));
        $this->assertInstanceOf(\RuntimeException::class, $e);
        $this->assertSame('test', $e->getMessage());
    }
}
