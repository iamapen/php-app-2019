<?php

namespace Acme\Support\Database\QueryBuilder;

use Acme\Test\Fake\Support\Database\Pdo\FakePdo;
use PHPUnit\Framework\TestCase;

class MysqlBulkInsertTest extends TestCase
{

    function test_buildStatement()
    {
        $sut = new MysqlBulkInsert();
        $pdo = new FakePdo();

        $rows = [
            ['name' => 'taro', 'age' => 20],
            ['name' => 'hanako', 'age' => 17],
        ];
        $ex = 'INSERT INTO users(name,age) VALUES(q_taro,q_20),(q_hanako,q_17)';
        $this->assertSame($ex, $sut->buildStatement('users', $rows, $pdo));
    }

    function test_buildStatement_行が空の場合例外()
    {
        $this->expectException(\InvalidArgumentException::class);
        $sut = new MysqlBulkInsert();
        $sut->buildStatement('users', [], new FakePdo());
    }

    function test_buildStatement_行が二次元表でない場合例外()
    {
        $this->expectException(\InvalidArgumentException::class);
        $sut = new MysqlBulkInsert();
        $sut->buildStatement('users', ['a', 'b'], new FakePdo());
    }
}
