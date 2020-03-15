<?php declare(strict_types=1);

namespace Support\Lock;

use Acme\Support\Lock\FLocker;
use PHPUnit\Framework\TestCase;

class FLockerTest extends TestCase
{
    private $lockfile;

    function setUp(): void
    {
        parent::setUp();
        $this->lockfile = sprintf('%s/lock%s', getenv('STORAGE_DIR'), '/lockfile01');
        @unlink($this->lockfile);
    }

    function testCreateByFullpath()
    {
        $locker1 = Flocker::ofFullpath($this->lockfile);
        $locker2 = Flocker::ofFullpath($this->lockfile);

        // ロック取得成功
        $this->assertTrue($locker1->getLock());

        // 取得不可
        $this->assertFalse($locker2->getLock());

        // 解放すれば取得可
        $this->assertTrue($locker1->unLock());
        $this->assertTrue($locker2->getLock());

        $locker2->unLock();
    }

    public function testCreateByResource()
    {
        $fp1 = fopen($this->lockfile, 'w+b');
        $locker1 = Flocker::ofResource($fp1);
        $fp2 = fopen($this->lockfile, 'w+b');
        $locker2 = Flocker::ofResource($fp2);

        // ロック取得成功
        $this->assertTrue($locker1->getLock());

        // 取得不可
        $this->assertFalse($locker2->getLock());

        // 解放すれば取得可
        $this->assertTrue($locker1->unLock());
        $this->assertTrue($locker2->getLock());

        $locker2->unLock();
    }

    public function testCreateByResource_fail()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('fp must be resource, "invalid" given');
        Flocker::ofResource('invalid');
    }

    public function testGetLock()
    {
        $this->assertFalse(file_exists($this->lockfile));
        $sut = Flocker::ofFullpath($this->lockfile);
        $this->assertFalse(file_exists($this->lockfile));

        $this->assertSame(true, $sut->getLock());
        $this->assertTrue(file_exists($this->lockfile));

        $sut->unLock();
    }

    function testGetLock_failed()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('open failed "/PATH/TO/invalid"');

        $sut = Flocker::ofFullpath('/PATH/TO/invalid');
        $sut->getLock();
    }

    public function testUnLock()
    {
        // ロック前はfalse
        $sut = Flocker::ofFullpath($this->lockfile);
        $this->assertFalse($sut->unLock());

        $this->assertTrue($sut->getLock());
        $this->assertTrue($sut->unLock());
    }

    public function testUnLockAndClose()
    {
        $fp = fopen($this->lockfile, 'w+b');
        $sut = Flocker::ofResource($fp);

        // closeされない
        $sut->unLock();
        $this->assertTrue(is_resource($fp));

        // closeされる
        $this->assertTrue($sut->unLockAndClose());
        $this->assertFalse(is_resource($fp));
    }

    public function testGetLockAndWait()
    {
        $this->markTestSkipped('1プロセスでは確認不可');
    }
}
