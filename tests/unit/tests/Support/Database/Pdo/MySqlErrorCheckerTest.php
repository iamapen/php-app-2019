<?php

namespace Support\Database\Pdo;

use Acme\Support\Database\Pdo\MySqlErrorChecker;
use Acme\Test\Fake\FakeMysqlTimeoutException;
use PHPUnit\Framework\TestCase;

class MySqlErrorCheckerTest extends TestCase
{
    function test_isTimeOutException()
    {
        $this->assertTrue(MySqlErrorChecker::isTimeOutException(new FakeMysqlTimeoutException()));
        // メッセージは違ってok
        $this->assertTrue(MySqlErrorChecker::isTimeOutException(new FakeMysqlTimeoutException('another message')));

        // codeが違うとNG
        $this->assertFalse(MySqlErrorChecker::isTimeOutException(new \PDOException()));

        // errorInfoの0番目が違うとNG
        $e = new FakeMysqlTimeoutException();
        $e->errorInfo[0] = 'invalid';
        $this->assertFalse(MySqlErrorChecker::isTimeOutException($e));

        // errorInfoの1番目が違うNG
        $e = new FakeMysqlTimeoutException();
        $e->errorInfo[1] = 2007;
        $this->assertFalse(MySqlErrorChecker::isTimeOutException($e));

        // errorInfoの2番目が違ってもok
        $e = new FakeMysqlTimeoutException();
        $e->errorInfo[2] = 'another message';
        $this->assertTrue(MySqlErrorChecker::isTimeOutException($e));
    }
}
