<?php

namespace Acme\Support\Utility;

use PHPUnit\Framework\TestCase;

class StringUtilTest extends TestCase
{
    function test_isPositiveInteger()
    {
        $this->assertFalse(StringUtil::isPositiveInterger(''));
        $this->assertFalse(StringUtil::isPositiveInterger(' '));
        $this->assertFalse(StringUtil::isPositiveInterger("\t"));

        $this->assertTrue(StringUtil::isPositiveInterger('1'));
        $this->assertTrue(StringUtil::isPositiveInterger('10'));
        $this->assertTrue(StringUtil::isPositiveInterger('101'));

        $this->assertFalse(StringUtil::isPositiveInterger('-1'));
        $this->assertFalse(StringUtil::isPositiveInterger('0'));
        $this->assertFalse(StringUtil::isPositiveInterger('1.0'));
        $this->assertFalse(StringUtil::isPositiveInterger('a'));
        $this->assertFalse(StringUtil::isPositiveInterger('01'));
        $this->assertFalse(StringUtil::isPositiveInterger('１'));
    }

    function test_isGteZeroInteger()
    {
        $this->assertFalse(StringUtil::isGteZeroInteger(''));
        $this->assertFalse(StringUtil::isGteZeroInteger(' '));
        $this->assertFalse(StringUtil::isGteZeroInteger("\t"));

        $this->assertTrue(StringUtil::isGteZeroInteger('1'));
        $this->assertTrue(StringUtil::isGteZeroInteger('10'));
        $this->assertTrue(StringUtil::isGteZeroInteger('101'));

        $this->assertFalse(StringUtil::isGteZeroInteger('-1'));
        $this->assertTrue(StringUtil::isGteZeroInteger('0'));
        $this->assertFalse(StringUtil::isGteZeroInteger('1.0'));
        $this->assertFalse(StringUtil::isGteZeroInteger('a'));
        $this->assertFalse(StringUtil::isGteZeroInteger('01'));
        $this->assertFalse(StringUtil::isGteZeroInteger('１'));
    }
}
