<?php declare(strict_types=1);

namespace Acme\App\Domain\User;

use PHPUnit\Framework\TestCase;

class PasswordTest extends TestCase
{

    public function test__construct()
    {
        $this->assertInstanceOf(Password::class, new Password('plain'));
    }

    public function testVerify()
    {
        $sut = new Password('$2y$10$frDwBa4GvWAsfmznuwPDEeqdh.FKyaTqRK8fpA1WXHvtstSLcmi86');
        $this->assertTrue($sut->verify('plain'));
    }

    public function testGenerate()
    {
        $this->assertInstanceOf(Password::class, Password::generate('plain'));
    }

    public function testHash()
    {
        $this->assertSame('$2y$10$', substr(Password::generate('plain')->hash(), 0, 7));
    }
}
