<?php

namespace Acme\Support\Database\Pdo;

use Acme\Test\Fake\Support\Database\Pdo\FakePdo;
use Acme\Test\Fake\Support\Database\Pdo\FakePdoStatement;

class ReconnectablePdoTest extends \Acme\Test\TestCase\BaseTestCase
{

    function test_createByPdo()
    {
        $sut = ReconnectablePdo::createByPdo(
            new FakePdo(),
            [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION],
            'mysql:host=localhost;dbname=foo;charset=utf8mb4',
            getenv('DB_MASTER_USERNAME'), getenv('DB_MASTER_PASSWORD')
        );
        $this->assertInstanceOf(ReconnectablePdo::class, $sut);
    }

    function test_createByClassName()
    {
        $sut = ReconnectablePdo::createByClassName(
            FakePdo::class,
            [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION],
            'mysql:host=localhost;dbname=foo;charset=utf8mb4',
            getenv('DB_MASTER_USERNAME'), getenv('DB_MASTER_PASSWORD')
        );
        $this->assertInstanceOf(ReconnectablePdo::class, $sut);
    }

    function test_createByClassName_PdoInterfaceでないとエラー()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('pdoClassName must be a Acme\Support\Database\Pdo\PdoInterface implemented class name, "stdClass" given');
        $sut = ReconnectablePdo::createByClassName(
            \stdClass::class,
            [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION],
            'mysql:host=localhost;dbname=foo;charset=utf8mb4',
            getenv('DB_MASTER_USERNAME'), getenv('DB_MASTER_PASSWORD')
        );
    }

    /**
     * 再接続のテスト
     * 失敗するとunittest DBのwait_timeoutが変わってしまうので注意
     * @group db
     */
    function test_reconnect()
    {
        $selectSql = 'SELECT 1 FROM dual';
        $ex = [1 => '1'];
        $pdo = $this->getPdo();
        $orgWaitTimeout = $pdo->query("SHOW GLOBAL VARIABLES LIKE 'wait_timeout'")->fetch()['Value'];
        // 要SUPER
        $pdo->exec('SET GLOBAL wait_timeout=1');

        $sut = ReconnectablePdo::createByClassName(Pdo::class, $this->getPdoOpts(), $this->getPdoDsn(),
            getenv('DB_MASTER_USERNAME'), getenv('DB_MASTER_PASSWORD'), $this->getPdoOpts());
        $this->assertSame($ex, $sut->query($selectSql)->fetch());

        // timeoutする
        $e = null;
        try {
            sleep(1);
            @$sut->query($selectSql)->fetch();
        } catch (\Throwable $e) {
        }
        $this->assertInstanceOf(\PDOException::class, $e);
        $this->assertSame('HY000', $e->getCode());
        $this->assertSame('SQLSTATE[HY000]: General error: 2006 MySQL server has gone away', $e->getMessage());

        // 再接続
        $sut->reconnect();
        $sut->exec("SET GLOBAL wait_timeout={$orgWaitTimeout}");
        $this->assertSame($ex, $sut->query($selectSql)->fetch());
    }

    function test_prepare()
    {
        $sut = ReconnectablePdo::createByPdo(new FakePdo());
        $stmt = $sut->prepare('SELECT 1 FROM dual WHERE 1=?');
        $this->assertInstanceOf(FakePdoStatement::class, $stmt);
        $this->assertSame('prepare', $stmt->errorCode());
    }

    public function test_exec()
    {
        $sut = ReconnectablePdo::createByPdo(new FakePdo());
        $this->assertSame(100, $sut->exec('SELECT 1 FROM dual WHERE 1=?'));
    }

    public function test_query()
    {
        $sut = ReconnectablePdo::createByPdo(new FakePdo());
        $stmt = $sut->query('SELECT 1 FROM dual WHERE 1=?');
        $this->assertInstanceOf(FakePdoStatement::class, $stmt);
        $this->assertSame('query', $stmt->errorCode());
    }

    public function test_quote()
    {
        $sut = ReconnectablePdo::createByPdo(new FakePdo());
        $this->assertSame('quote', $sut->quote('SELECT 1 FROM dual WHERE 1=?'));
    }

    public function test_lastInsertId()
    {
        $sut = ReconnectablePdo::createByPdo(new FakePdo());
        $this->assertSame('lastInsertId', $sut->lastInsertId());
    }

    public function test_errorCode()
    {
        $sut = ReconnectablePdo::createByPdo(new FakePdo());
        $this->assertSame('errorCode', $sut->errorCode());
    }

    public function test_errorInfo()
    {
        $sut = ReconnectablePdo::createByPdo(new FakePdo());
        $this->assertSame(['errorInfo'], $sut->errorInfo());
    }

    public function test_setAttribute()
    {
        $sut = ReconnectablePdo::createByPdo(new FakePdo());
        $this->assertTrue($sut->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION));
    }

    public function test_getAttribute()
    {
        $sut = ReconnectablePdo::createByPdo(new FakePdo());
        $this->assertSame('getAttribute', $sut->getAttribute(\PDO::ATTR_ERRMODE));
    }

    function test_getAvailableDrivers()
    {
        $this->assertSame(\PDO::getAvailableDrivers(), ReconnectablePdo::getAvailableDrivers());
    }

    public function test_inTransaction()
    {
        $sut = ReconnectablePdo::createByPdo(new FakePdo());
        $this->assertTrue($sut->inTransaction());
    }

    public function test_beginTransaction()
    {
        $sut = ReconnectablePdo::createByPdo(new FakePdo());
        $this->assertTrue($sut->beginTransaction());
    }

    public function test_commit()
    {
        $sut = ReconnectablePdo::createByPdo(new FakePdo());
        $this->assertTrue($sut->commit());
    }

    public function test_rollback()
    {
        $sut = ReconnectablePdo::createByPdo(new FakePdo());
        $this->assertTrue($sut->rollback());
    }
}
