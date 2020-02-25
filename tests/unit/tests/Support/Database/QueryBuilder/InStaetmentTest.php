<?php

namespace Acme\Support\Database\QueryBuilder;

use PHPUnit\Framework\TestCase;

class InStaetmentTest extends TestCase
{

    public function test__construct()
    {
        $sut = new InStaetment('status', [1, 2]);
        $this->assertSame(InStaetment::class, get_class($sut));
    }

    public function test_buildStatement()
    {
        $sut = new InStaetment('status', ['new', 'opened', 'closed']);
        $this->assertSame(
            'IN(:status_1,:status_2,:status_3) ',
            $sut->buildStatement()
        );

    }

    public function test_getParameters()
    {
        $sut = new InStaetment('status', ['new', 'opened', 'closed']);
        $ex = [
            'status_1' => 'new',
            'status_2' => 'opened',
            'status_3' => 'closed',
        ];
        // ビルド前は空
        $this->assertSame([], $sut->getParameters());

        $sut->buildStatement();
        $this->assertSame($ex, $sut->getParameters());
    }

    public function test_buildStatement_empty()
    {
        $sut = new InStaetment('status', []);
        $this->assertSame('', $sut->buildStatement());
        $this->assertSame([], $sut->getParameters());
    }
}
