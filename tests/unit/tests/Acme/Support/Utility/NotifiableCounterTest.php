<?php

namespace Acme\Support\Utility;

use PHPUnit\Framework\TestCase;

class NotifiableCounterTest extends TestCase
{
    public function test__construct()
    {
        $sut = new NotifiableCounter(function () { });
        $this->assertInstanceOf(NotifiableCounter::class, $sut);
    }

    public function testGetCount()
    {
        $sut = new NotifiableCounter(function () { });
        $this->assertSame(0, $sut->getCount());

        $sut->increment();
        $this->assertSame(1, $sut->getCount());
    }

    public function test_callback_5回ごと()
    {
        $msgs = [];
        $sut = new NotifiableCounter(function ($loop) use (&$msgs) {
            $msgs[] = $loop;
        }, 5);

        $ex = [5, 10, 15, 20];
        for ($i = 0; $i < 20; $i++) {
            $sut->increment();
        }
        $this->assertSame($ex, $msgs);
    }

    public function test_callback_10回ごと、最初の5回は常に出す()
    {
        $msgs = [];
        $sut = new NotifiableCounter(function ($loop) use (&$msgs) {
            $msgs[] = $loop;
        }, 10, [1, 2, 3, 4, 5]);

        $ex = [1,2,3,4,5,10,20];
        for ($i = 0; $i < 20; $i++) {
            $sut->increment();
        }
        $this->assertSame($ex, $msgs);
    }
}
