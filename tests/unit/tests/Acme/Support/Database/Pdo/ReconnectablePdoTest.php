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

    /**
     * @group db
     */
    function test_reconnectIfTimetouted_timeout起きない場合()
    {
        $sut = ReconnectablePdo::createByClassName(Pdo::class, $this->getPdoOpts(), $this->getPdoDsn(),
            getenv('DB_MASTER_USERNAME'), getenv('DB_MASTER_PASSWORD'), $this->getPdoOpts());

        $this->assertFalse($sut->reconnectIfTimeouted());
    }

    /**
     * 失敗するとunittest DBのwait_timeoutが変わってしまうので注意
     * @group db
     */
    function test_reconnectIfTimetouted_timeout起きた場合()
    {
        $pdo = $this->getPdo();
        $orgWaitTimeout = $pdo->query("SHOW GLOBAL VARIABLES LIKE 'wait_timeout'")->fetch()['Value'];
        // 要SUPER
        $pdo->exec('SET GLOBAL wait_timeout=1');

        $sut = ReconnectablePdo::createByClassName(Pdo::class, $this->getPdoOpts(), $this->getPdoDsn(),
            getenv('DB_MASTER_USERNAME'), getenv('DB_MASTER_PASSWORD'), $this->getPdoOpts());
        sleep(1);
        $this->assertTrue(@$sut->reconnectIfTimeouted());

        $sut->exec("SET GLOBAL wait_timeout={$orgWaitTimeout}");
    }

    function test_prepare()
    {
        $pdo = new FakePdo();
        $sut = ReconnectablePdo::createByPdo($pdo);
        $stmt = $sut->prepare('SELECT 1 FROM dual WHERE 1=?');
        $this->assertInstanceOf(FakePdoStatement::class, $stmt);
        $this->assertSame(['prepare'], $pdo->arrMsg);
    }

    public function test_exec()
    {
        $pdo = new FakePdo();
        $sut = ReconnectablePdo::createByPdo($pdo);
        $this->assertSame(100, $sut->exec('SELECT 1 FROM dual WHERE 1=?'));
        $this->assertSame(['exec'], $pdo->arrMsg);
    }

    public function test_query()
    {
        $pdo = new FakePdo();
        $sut = ReconnectablePdo::createByPdo($pdo);
        $stmt = $sut->query('SELECT 1 FROM dual WHERE 1=?');
        $this->assertInstanceOf(FakePdoStatement::class, $stmt);
        $this->assertSame(['query'], $pdo->arrMsg);
    }

    public function test_quote()
    {
        $pdo = new FakePdo();
        $sut = ReconnectablePdo::createByPdo($pdo);
        $this->assertSame('q_abc', $sut->quote('abc'));
        $this->assertSame(['quote'], $pdo->arrMsg);
    }

    public function test_lastInsertId()
    {
        $pdo = new FakePdo();
        $sut = ReconnectablePdo::createByPdo($pdo);
        $this->assertSame('lastInsertId', $sut->lastInsertId());
        $this->assertSame(['lastInsertId'], $pdo->arrMsg);
    }

    public function test_errorCode()
    {
        $pdo = new FakePdo();
        $sut = ReconnectablePdo::createByPdo($pdo);
        $this->assertSame('errorCode', $sut->errorCode());
        $this->assertSame(['errorCode'], $pdo->arrMsg);
    }

    public function test_errorInfo()
    {
        $pdo = new FakePdo();
        $sut = ReconnectablePdo::createByPdo($pdo);
        $this->assertSame(['errorInfo'], $sut->errorInfo());
        $this->assertSame(['errorInfo'], $pdo->arrMsg);
    }

    public function test_setAttribute()
    {
        $sut = ReconnectablePdo::createByPdo(new FakePdo());
        $this->assertTrue($sut->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION));
    }

    public function test_getAttribute()
    {
        $pdo = new FakePdo();
        $sut = ReconnectablePdo::createByPdo($pdo);
        $this->assertSame('getAttribute', $sut->getAttribute(\PDO::ATTR_ERRMODE));
        $this->assertSame(['getAttribute'], $pdo->arrMsg);
    }

    function test_getAvailableDrivers()
    {
        $this->assertSame(\PDO::getAvailableDrivers(), ReconnectablePdo::getAvailableDrivers());
    }

    public function test_inTransaction()
    {
        $pdo = new FakePdo();
        $sut = ReconnectablePdo::createByPdo($pdo);
        $this->assertTrue($sut->inTransaction());
        $this->assertSame(['inTransaction'], $pdo->arrMsg);
    }

    public function test_beginTransaction()
    {
        $pdo = new FakePdo();
        $sut = ReconnectablePdo::createByPdo($pdo);
        $this->assertTrue($sut->beginTransaction());
        $this->assertSame(['beginTransaction'], $pdo->arrMsg);
    }

    public function test_commit()
    {
        $pdo = new FakePdo();
        $sut = ReconnectablePdo::createByPdo($pdo);
        $this->assertTrue($sut->commit());
        $this->assertSame(['commit'], $pdo->arrMsg);
    }

    public function test_rollback()
    {
        $pdo = new FakePdo();
        $sut = ReconnectablePdo::createByPdo($pdo);
        $this->assertTrue($sut->rollback());
        $this->assertSame(['rollback'], $pdo->arrMsg);
    }
}
