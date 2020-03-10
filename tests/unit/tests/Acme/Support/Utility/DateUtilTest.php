<?php declare(strict_types=1);

namespace Acme\Support\Utility;

use PHPUnit\Framework\TestCase;

class DateUtilTest extends TestCase
{

    function test_isDateString()
    {
        $this->assertTrue(DateUtil::isDateString('2016-01-01'));
        $this->assertFalse(DateUtil::isDateString('2016/01/01'));

        // 時刻が入ってはならない
        $this->assertFalse(DateUtil::isDateString('2016-01-01 00:00:00'));
        // 10桁でないとならない
        $this->assertFalse(DateUtil::isDateString('2016-1-1'));
        $this->assertFalse(DateUtil::isDateString('2016-01-1'));
        $this->assertFalse(DateUtil::isDateString('2016-1-01'));

        // 存在しない日付
        $this->assertFalse(DateUtil::isDateString('1999-99-99'));
        $this->assertFalse(DateUtil::isDateString('1999-09-31'));
    }

    function test_isDateTimeString()
    {
        $this->assertTrue(DateUtil::isDateTimeString('2016-01-01 01:02:03'));
        $this->assertFalse(DateUtil::isDateTimeString('2016/01/01 01:02:03'));
        $this->assertFalse(DateUtil::isDateTimeString(''));

        // 時刻が必要
        $this->assertFalse(DateUtil::isDateTimeString('2016-01-01'));
        // 19桁でないとならない
        $this->assertFalse(DateUtil::isDateTimeString('2016-1-1 01:02:03'));
        $this->assertFalse(DateUtil::isDateTimeString('2016-01-1 01:02:03'));
        $this->assertFalse(DateUtil::isDateTimeString('2016-1-01 01:02:03'));
        $this->assertFalse(DateUtil::isDateTimeString('2016-01-01 1:02:03'));
        $this->assertFalse(DateUtil::isDateTimeString('2016-01-01 01:2:03'));
        $this->assertFalse(DateUtil::isDateTimeString('2016-01-01 01:02:3'));

        // 存在しない日付
        $this->assertFalse(DateUtil::isDateTimeString('1999-99-99 01:02:03'));
        $this->assertFalse(DateUtil::isDateTimeString('2016-01-01 99:99:99'));
        $this->assertFalse(DateUtil::isDateTimeString('1999-09-31 01:02:03'));
    }
}
