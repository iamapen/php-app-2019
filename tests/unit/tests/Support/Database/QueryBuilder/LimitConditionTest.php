<?php

namespace Acme\Support\Database\QueryBuilder;

use PHPUnit\Framework\TestCase;

class LimitConditionTest extends TestCase
{
    public function test_createByArray()
    {
        $sut = LimitCondition::createByArray([]);
        $this->assertSame('', $sut->buildSelectPartStatement([]));
        $this->assertSame('', $sut->buildLimitPartStatement());

        $sut = LimitCondition::createByArray(['id', 'name', 'b']);
        $this->assertSame('', $sut->buildSelectPartStatement([]));
        $this->assertSame('', $sut->buildLimitPartStatement());

        $sut = LimitCondition::createByArray(['id', 'name', 'b'], new Limit(10));
        $this->assertSame('SELECT id, name, a AS b ', $sut->buildSelectPartStatement(['id', 'name', 'b' => 'a AS b']));
        $this->assertSame('LIMIT 10 ', $sut->buildLimitPartStatement());

        $sut = LimitCondition::createByArray(['id', 'name', 'b'], new Limit(10, 3));
        $this->assertSame('SELECT id, name, a AS b ', $sut->buildSelectPartStatement(['id', 'name', 'b' => 'a AS b']));
        $this->assertSame('LIMIT 10 OFFSET 3 ', $sut->buildLimitPartStatement());

    }

    function test_getSelectFields() {
        $sut = LimitCondition::createByArray([]);
        $this->assertSame([], $sut->getSelectFields());

        $sut = LimitCondition::createByArray(['id', 'name', 'b']);
        $this->assertSame(['id', 'name', 'b'], $sut->getSelectFields());
    }

    function test_getLimit() {
        $sut = LimitCondition::createByArray([]);
        $this->assertSame(null, $sut->getLimit());

        $sut = LimitCondition::createByArray([], new Limit(50, 15));
        $this->assertInstanceOf(Limit::class, $sut->getLimit());
        $this->assertSame(50, $sut->getLimit()->getLimit());
        $this->assertSame(15, $sut->getLimit()->getOffset());
    }

    function test_buildSelectPartStatement()
    {
        $sql = '   FROM users u';
        $sql .= ' INNER JOIN address a';
        $sql .= '    ON u.id = a.user_id';
        $arrSelect = ['name' => 'u.name', 'id' => 'a.id', 'foo' => 'a.city AS foo'];

        $sut = LimitCondition::createByArray(['id', 'name', 'foo']);
        $this->assertSame(
            'SELECT u.name, a.id, a.city AS foo ',
            $sut->buildSelectPartStatement($arrSelect)
        );

        $sut = LimitCondition::createByArray(['id', 'name']);
        $this->assertSame(
            'SELECT u.name, a.id ',
            $sut->buildSelectPartStatement($arrSelect)
        );

        $sut = LimitCondition::createByArray(['name', 'id']);
        $this->assertSame(
            'SELECT u.name, a.id ',
            $sut->buildSelectPartStatement($arrSelect)
        );

        $sut = LimitCondition::createByArray([]);
        $this->assertSame(
            '',
            $sut->buildSelectPartStatement($arrSelect)
        );
    }
}
