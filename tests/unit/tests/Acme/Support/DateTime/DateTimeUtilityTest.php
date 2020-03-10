<?php

namespace Acme\Support\DateTime;

use PHPUnit\Framework\TestCase;

class DateTimeUtilityTest extends TestCase
{

    function test_toJpWeek()
    {
        $this->assertSame('日', DateTimeUtility::toJpWeek(0));
        $this->assertSame('月', DateTimeUtility::toJpWeek(1));
        $this->assertSame('火', DateTimeUtility::toJpWeek(2));
        $this->assertSame('水', DateTimeUtility::toJpWeek(3));
        $this->assertSame('木', DateTimeUtility::toJpWeek(4));
        $this->assertSame('金', DateTimeUtility::toJpWeek(5));
        $this->assertSame('土', DateTimeUtility::toJpWeek(6));
        $this->assertSame(null, DateTimeUtility::toJpWeek(7));
    }

    function test_secondsToHms()
    {
        $this->assertSame('00:00:00', DateTimeUtility::secondsToHms(0));
        $this->assertSame('00:00:01', DateTimeUtility::secondsToHms(1));
        $this->assertSame('00:00:59', DateTimeUtility::secondsToHms(59));
        $this->assertSame('00:01:00', DateTimeUtility::secondsToHms(60));
        $this->assertSame('00:59:59', DateTimeUtility::secondsToHms(3599));
        $this->assertSame('01:00:00', DateTimeUtility::secondsToHms(3600));
        $this->assertSame('23:59:59', DateTimeUtility::secondsToHms(86399));
        $this->assertSame('24:00:00', DateTimeUtility::secondsToHms(86400));
        $this->assertSame('24:00:01', DateTimeUtility::secondsToHms(86401));
        $this->assertSame('99:59:59', DateTimeUtility::secondsToHms(359999));
        $this->assertSame('100:00:00', DateTimeUtility::secondsToHms(360000));

        $this->assertSame('00:00:30', DateTimeUtility::secondsToHms(-30));
    }

    function test_diffToHms()
    {
        $this->assertSame('00:00:30', DateTimeUtility::diffToHms(new \DateTime('2019-11-20 10:11:12'), new \DateTime('2019-11-20 10:11:42')));
    }

    function test_diffToHmsByInt()
    {
        $this->assertSame('00:00:30', DateTimeUtility::diffToHmsByInt(100, 130));
    }
}
