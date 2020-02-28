<?php

namespace Acme\Support\Database\QueryBuilder;

use PHPUnit\Framework\TestCase;

class LimitTest extends TestCase
{
    public function test__construct()
    {
        $sut = new Limit(10, 20);
        $this->assertInstanceOf(Limit::class, $sut);
        $this->assertSame(10, $sut->getLimit());
        $this->assertSame(20, $sut->getOffset());
    }

    public function testCreateByOffset()
    {
        $sut = Limit::createByOffset(10, 20);
        $this->assertInstanceOf(Limit::class, $sut);
        $this->assertSame(10, $sut->getLimit());
        $this->assertSame(20, $sut->getOffset());
    }

    public function testCreateByPageNo()
    {
        $sut = Limit::createByPageNo(10, 3);
        $this->assertInstanceOf(Limit::class, $sut);
        $this->assertSame(10, $sut->getLimit());
        $this->assertSame(20, $sut->getOffset());
    }
}
