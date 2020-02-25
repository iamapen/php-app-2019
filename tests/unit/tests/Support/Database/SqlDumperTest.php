<?php

namespace Acme\Support\Database;

class SqlDumperTest extends \PHPUnit\Framework\TestCase
{
    function test_dump_classicPlaceholder()
    {
        $sql = 'SELECT col1, col2 FROM t1 WHERE col1=? AND col2=?';
        $params = ['1', '2'];
        $this->assertSame(
            "SELECT col1, col2 FROM t1 WHERE col1='1' AND col2='2'",
            SqlDumper::dump($sql, $params)
        );

        $sql = 'SELECT col1, col2 FROM t1 WHERE col1=? AND col2=?';
        $params = [1, 2];
        $this->assertSame(
            "SELECT col1, col2 FROM t1 WHERE col1=1 AND col2=2",
            SqlDumper::dump($sql, $params)
        );

        $sql = 'SELECT col1, col2 FROM t1 WHERE col1=? AND col2=?';
        $params = [1.1, 1.2];
        $this->assertSame(
            "SELECT col1, col2 FROM t1 WHERE col1=1.1 AND col2=1.2",
            SqlDumper::dump($sql, $params)
        );

        $sql = 'SELECT col1, col2 FROM t1 WHERE col1=? AND col2=?';
        $params = [null, null];
        $this->assertSame(
            "SELECT col1, col2 FROM t1 WHERE col1=NULL AND col2=NULL",
            SqlDumper::dump($sql, $params)
        );
    }

    function test_dump_namedPlaceholder()
    {
        $sql = 'SELECT col1, col2 FROM t1 WHERE col1=:col1 AND col2=:col2';
        $params = ['col2' => '2', 'col1' => '1'];
        $this->assertSame(
            "SELECT col1, col2 FROM t1 WHERE col1='1' AND col2='2'",
            SqlDumper::dump($sql, $params)
        );

        $sql = 'SELECT col1, col2 FROM t1 WHERE col1=:col1 AND col2=:col2';
        $params = [':col2' => '2', ':col1' => '1'];
        $this->assertSame(
            "SELECT col1, col2 FROM t1 WHERE col1='1' AND col2='2'",
            SqlDumper::dump($sql, $params)
        );
    }
}
