<?php declare(strict_types=1);

namespace Support\Database\Pdo;

use Acme\Support\Database\Pdo\NoopPdo;
use Acme\Support\Database\Pdo\NoopPdoStatement;
use PHPUnit\Framework\TestCase;

class NoopPdoTest extends TestCase
{
    function test_prepare()
    {
        $pdo = new NoopPdo();
        $stmt = $pdo->prepare('SELECT 1 FROM dual');
        $this->assertInstanceOf(NoopPdoStatement::class, $stmt);
    }

    function test_exec()
    {
        $pdo = new NoopPdo();
        $this->assertSame(0, $pdo->exec("INSERT INTO users(id, name) VALUES(1, 'arare')"));
    }

    function test_query()
    {
        $pdo = new NoopPdo();
        $stmt = $pdo->query('SELECT 1 FROM dual');
        $this->assertInstanceOf(NoopPdoStatement::class, $stmt);
    }

    function test_quote()
    {
        $pdo = new NoopPdo();
        $this->assertSame('1', $pdo->quote('1'));
    }

    function test_lastInsertId()
    {
        $pdo = new NoopPdo();
        $this->assertSame('1', $pdo->lastInsertId());
    }

    function test_errorCode()
    {
        $pdo = new NoopPdo();
        $this->assertSame(null, $pdo->errorCode());
    }

    function test_errorInfo()
    {
        $pdo = new NoopPdo();
        $this->assertSame([], $pdo->errorInfo());
    }

    function test_setAttribute()
    {
        $pdo = new NoopPdo();
        $this->assertSame(true, $pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, \PDO::ERRMODE_EXCEPTION));
    }

    function test_getAttribute()
    {
        $pdo = new NoopPdo();
        $this->assertSame(\PDO::ERRMODE_EXCEPTION, $pdo->getAttribute(\PDO::ATTR_EMULATE_PREPARES));
    }

    function test_getAvailableDrivers()
    {
        $this->assertSame([], NoopPdo::getAvailableDrivers());
    }

    function test_inTransaction()
    {
        $pdo = new NoopPdo();
        $this->assertSame(true, $pdo->inTransaction());
    }

    function test_beginTransaction()
    {
        $pdo = new NoopPdo();
        $this->assertSame(true, $pdo->beginTransaction());
    }

    function test_commit()
    {
        $pdo = new NoopPdo();
        $this->assertSame(true, $pdo->commit());
    }

    function test_rollback()
    {
        $pdo = new NoopPdo();
        $this->assertSame(true, $pdo->rollback());
    }

}

