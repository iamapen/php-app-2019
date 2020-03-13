<?php declare(strict_types=1);

namespace Acme\App\Domain\User;

use PHPUnit\Framework\TestCase;

class EmailAddressTest extends TestCase
{

    public function test__construct()
    {
        $this->assertInstanceOf(EmailAddress::class, new EmailAddress('foo@example.com'));
    }

    public function test__construct_fail()
    {
        @$this->expectException(\InvalidArgumentException::class);
        @$this->expectExceptionMessage('Invalid email address "abc" given');
        $this->assertInstanceOf(EmailAddress::class, new EmailAddress('abc'));
    }

    public function testIsValid()
    {
        $this->assertTrue(EmailAddress::isValid('foo@example.com'));
        $this->assertFalse(EmailAddress::isValid('abc'));
    }

    public function test__toString()
    {
        $sut = new EmailAddress('foo@example.com');
        $this->assertSame('foo@example.com', (string)$sut);
    }

    public function testAddress()
    {
        $sut = new EmailAddress('foo@example.com');
        $this->assertSame('foo@example.com', $sut->address());
    }
}
