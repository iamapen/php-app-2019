<?php declare(strict_types=1);

namespace Support\Lock;

use Acme\Support\Lock\MkdirLocker;
use PHPUnit\Framework\TestCase;

class MkdirLockerTest extends TestCase
{
    private $lockDir;

    function setUp(): void
    {
        parent::setUp();
        $this->lockDir = sprintf('%s/lock%s', getenv('STORAGE_DIR'), '/lockdir01');
        @rmdir($this->lockDir);
    }

    function testCreateByFullpath()
    {
        $locker1 = MkdirLocker::createByFullpath($this->lockDir);
        $locker2 = MkdirLocker::createByFullpath($this->lockDir);

        // ロック取得成功
        $this->assertTrue($locker1->getLock());

        // 取得不可
        $this->assertFalse($locker2->getLock());

        // 解放すれば取得可
        $this->assertTrue($locker1->unLock());
        $this->assertTrue($locker2->getLock());

        $locker2->unLock();
    }

    public function testCreateByFullpath_fail()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('directory does not exists "/PATH/TO"');
        $sut = MkdirLocker::createByFullpath('/PATH/TO/invalid');
    }

    public function testGetLock()
    {
        $sut = MkdirLocker::createByFullpath($this->lockDir);
        $this->assertFalse(file_exists($this->lockDir));

        $this->assertTrue($sut->getLock());
        $this->assertTrue(file_exists($this->lockDir));

        $sut->unLock();
    }

    public function testUnLock()
    {
        // ロック前はfalse
        $sut = MkdirLocker::createByFullpath($this->lockDir);
        $this->assertFalse($sut->unLock());

        $this->assertTrue($sut->getLock());
        $this->assertTrue($sut->unLock());
    }
}
