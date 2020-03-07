<?php

namespace Acme\Support\Database\QueryBuilder;

use Acme\Support\Database\DoctrineDbal\NullConnection;
use Acme\Support\Database\DoctrineDbal\NullMysqlDriver;
use Doctrine\DBAL\Query\QueryBuilder;
use PHPUnit\Framework\TestCase;

class LimitConditionTest extends TestCase
{
    public function test_createByArray()
    {
        $sut = LimitCondition::create([]);
        $this->assertSame('SELECT ', $sut->buildSelectPartStatement([]));
        $this->assertSame('', $sut->buildLimitPartStatement());

        $sut = LimitCondition::create(['id', 'name', 'b']);
        $this->assertSame('SELECT ', $sut->buildSelectPartStatement([]));
        $this->assertSame('', $sut->buildLimitPartStatement());

        $sut = LimitCondition::create(['id', 'name', 'b'], new Limit(10));
        $this->assertSame('SELECT id, name, a AS b ', $sut->buildSelectPartStatement(['id', 'name', 'b' => 'a AS b']));
        $this->assertSame(' LIMIT 10 ', $sut->buildLimitPartStatement());

        $sut = LimitCondition::create(['id', 'name', 'b'], new Limit(10, 3));
        $this->assertSame('SELECT id, name, a AS b ', $sut->buildSelectPartStatement(['id', 'name', 'b' => 'a AS b']));
        $this->assertSame(' LIMIT 10 OFFSET 3 ', $sut->buildLimitPartStatement());

    }

    function test_getSelectFields() {
        $sut = LimitCondition::create([]);
        $this->assertSame([], $sut->getSelectFields());

        $sut = LimitCondition::create(['id', 'name', 'b']);
        $this->assertSame(['id', 'name', 'b'], $sut->getSelectFields());
    }

    function test_getLimit() {
        $sut = LimitCondition::create([]);
        $this->assertSame(null, $sut->getLimit());

        $sut = LimitCondition::create([], new Limit(50, 15));
        $this->assertInstanceOf(Limit::class, $sut->getLimit());
        $this->assertSame(50, $sut->getLimit()->getLimit());
        $this->assertSame(15, $sut->getLimit()->getOffset());
    }

    function test_mergeFromQb() {
        $qb = (new QueryBuilder(new NullConnection(new NullMysqlDriver())))
            ->select(['address', 'gender']);

        $sut = LimitCondition::create(['id']);
        $result = $sut->mergeFromQb($qb);
        $this->assertSame(['id', 'address', 'gender'], $result->getSelectFields());
    }

    function test_mergeFromQb_重複しないこと() {
        $qb = (new QueryBuilder(new NullConnection(new NullMysqlDriver())))
            ->select(['address', 'gender']);

        $sut = LimitCondition::create(['id', 'address']);
        $result = $sut->mergeFromQb($qb);
        $this->assertSame(['id', 'address', 'gender'], $result->getSelectFields());
    }

    function test_mergeToQb() {
        $qb = (new QueryBuilder(new NullConnection(new NullMysqlDriver())))
            ->select(['address', 'gender']);

        $sut = LimitCondition::create(['id']);
        $result = $sut->mergeToQb($qb, ['id', 'address', 'gender', 'first_name', 'last_name']);
        $this->assertSame(['id', 'address', 'gender'], $result->getQueryPart('select'));
        $this->assertSame(null, $result->getMaxResults());
        $this->assertSame(null, $result->getFirstResult());
    }

    function test_mergeToQb_重複しないこと() {
        $qb = (new QueryBuilder(new NullConnection(new NullMysqlDriver())))
            ->select(['address', 'gender']);

        $sut = LimitCondition::create(['id', 'address']);
        $result = $sut->mergeToQb($qb, ['id', 'address', 'gender', 'first_name', 'last_name']);
        $this->assertSame(['id', 'address', 'gender'], $result->getQueryPart('select'));
        $this->assertSame(null, $result->getMaxResults());
        $this->assertSame(null, $result->getFirstResult());
    }

    function test_mergeToQb_withLimit() {
        $qb = (new QueryBuilder(new NullConnection(new NullMysqlDriver())))
            ->select(['address', 'gender']);

        $sut = LimitCondition::create(['id'], new Limit(10, 20));
        $result = $sut->mergeToQb($qb, ['id', 'address', 'gender', 'first_name', 'last_name']);
        $this->assertSame(['id', 'address', 'gender'], $result->getQueryPart('select'));
        $this->assertSame(10, $result->getMaxResults());
        $this->assertSame(20, $result->getFirstResult());
    }

    function test_buildSelectPartStatement()
    {
        $sql = '   FROM users u';
        $sql .= ' INNER JOIN address a';
        $sql .= '    ON u.id = a.user_id';
        $arrSelect = ['name' => 'u.name', 'id' => 'a.id', 'foo' => 'a.city AS foo'];

        $sut = LimitCondition::create(['id', 'name', 'foo']);
        $this->assertSame(
            'SELECT u.name, a.id, a.city AS foo ',
            $sut->buildSelectPartStatement($arrSelect)
        );

        $sut = LimitCondition::create(['id', 'name']);
        $this->assertSame(
            'SELECT u.name, a.id ',
            $sut->buildSelectPartStatement($arrSelect)
        );

        $sut = LimitCondition::create(['name', 'id']);
        $this->assertSame(
            'SELECT u.name, a.id ',
            $sut->buildSelectPartStatement($arrSelect)
        );

        $sut = LimitCondition::create([]);
        $this->assertSame(
            'SELECT u.name, a.id, a.city AS foo ',
            $sut->buildSelectPartStatement($arrSelect)
        );
    }
}
