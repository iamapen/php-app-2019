<?php declare(strict_types=1);

namespace App\Domain\Models;

use Acme\App\Domain\Models\Gender;
use PHPUnit\Framework\TestCase;

class GenderTest extends TestCase
{
    function test_construct()
    {
        $this->assertSame(Gender::MALE, Gender::MALE()->getValue());
        $this->assertSame(Gender::FEMALE, Gender::FEMALE()->getValue());
    }

    function test_specification()
    {
        $sut = Gender::MALE();

        $this->assertInstanceOf(Gender::class, $sut);
        $this->assertSame('m', $sut->getValue());
        $this->assertSame('MALE', $sut->getKey());

        $this->assertFalse($sut->equals('m'));
        $this->assertFalse($sut->equals('MALE'));
        $this->assertFalse($sut->equals(Gender::FEMALE()));
        $this->assertTrue($sut->equals(Gender::MALE()));
    }
}
