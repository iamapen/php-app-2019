<?php

namespace Acme\Support\Database\Pdo;

use Acme\Test\Fake\FakeMysqlTimeoutException;
use Acme\Test\Fake\Support\Database\Pdo\FakePdo;
use Acme\Test\Fake\Support\Database\Pdo\FakePdoStatement;

class ReconnectablePdoTest extends \Acme\Test\DbBaseTestCase
{

    function test_createByPdo()
    {
        $sut = ReconnectablePdo::createByPdo($this->getPdo(), $this->getPdoOpts(), $this->getPdoDsn(),
            getenv('DB_MASTER_USERNAME'), getenv('DB_MASTER_PASSWORD'));
        $this->assertInstanceOf(ReconnectablePdo::class, $sut);
    }

    function test_createByClassName()
    {
        $sut = ReconnectablePdo::createByClassName(Pdo::class, $this->getPdoOpts(), $this->getPdoDsn(),
            getenv('DB_MASTER_USERNAME'), getenv('DB_MASTER_PASSWORD'), $this->getPdoOpts());
        $this->assertInstanceOf(ReconnectablePdo::class, $sut);
    }

    function test_createByClassName_PdoInterfaceでないとエラー()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('pdoClassName must be a Acme\Support\Database\Pdo\PdoInterface implemented class name, "PDO" given');
        $sut = ReconnectablePdo::createByClassName(
            \PDO::class, $this->getPdoOpts(), $this->getPdoDsn(),
            getenv('DB_MASTER_USERNAME'), getenv('DB_MASTER_PASSWORD'), $this->getPdoOpts()
        );
    }

    /**
     * 再接続のテスト
     * 失敗するとunittest DBのwait_timeoutが変わってしまうので注意
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

    function test_isTimeOutException()
    {
        $this->assertTrue(ReconnectablePdo::isTimeOutException(new FakeMysqlTimeoutException()));
        // メッセージは違ってok
        $this->assertTrue(ReconnectablePdo::isTimeOutException(new FakeMysqlTimeoutException('another message')));

        // codeが違うとNG
        $this->assertFalse(ReconnectablePdo::isTimeOutException(new \PDOException()));

        // errorInfoの0番目が違うとNG
        $e = new FakeMysqlTimeoutException();
        $e->errorInfo[0] = 'invalid';
        $this->assertFalse(ReconnectablePdo::isTimeOutException($e));

        // errorInfoの1番目が違うNG
        $e = new FakeMysqlTimeoutException();
        $e->errorInfo[1] = 2007;
        $this->assertFalse(ReconnectablePdo::isTimeOutException($e));

        // errorInfoの2番目が違ってもok
        $e = new FakeMysqlTimeoutException();
        $e->errorInfo[2] = 'another message';
        $this->assertTrue(ReconnectablePdo::isTimeOutException($e));
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
