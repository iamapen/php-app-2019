<?php declare(strict_types=1);

namespace Support\Database\Pdo;

use Acme\Support\Database\Pdo\NoopPdo;
use PHPUnit\Framework\TestCase;

class NoopPdoStatementTest extends TestCase
{
    public function test_bindValue()
    {
        $sut = (new NoopPdo())->prepare('SELECT 1 FROM dual');
        $this->assertTrue($sut->bindValue('id', 1));
    }

    function test_errorCode()
    {
        $sut = (new NoopPdo())->prepare('SELECT 1 FROM dual');
        $this->assertSame(null, $sut->errorCode());
    }

    function test_errorInfo()
    {
        $sut = (new NoopPdo())->prepare('SELECT 1 FROM dual');
        $this->assertSame([], $sut->errorInfo());
    }

    function test_execute()
    {
        $sut = (new NoopPdo())->prepare('SELECT 1 FROM dual');
        $this->assertTrue($sut->execute(['id' => 1]));
    }

    function test_fetch()
    {
        $sut = (new NoopPdo())->prepare('SELECT 1 FROM dual');
        $sut->execute(['id' => 1]);
        $this->assertFalse($sut->fetch());
    }

    function test_fetchAll()
    {
        $sut = (new NoopPdo())->prepare('SELECT 1 FROM dual');
        $sut->execute(['id' => 1]);
        $this->assertSame([], $sut->fetchAll());
    }

    function test_fetchColumn()
    {
        $sut = (new NoopPdo())->prepare('SELECT 1 FROM dual');
        $sut->execute(['id' => 1]);
        $this->assertFalse($sut->fetchColumn(0));
    }

    function test_rowCount()
    {
        $sut = (new NoopPdo())->prepare('INSERT INTO users(id) VALUES(1)');
        $sut->execute([]);
        $this->assertSame(0, $sut->rowCount());
    }

    function test_setFetchMode()
    {
        $sut = (new NoopPdo())->prepare('INSERT INTO users(id) VALUES(1)');
        $sut->execute([]);
        $this->assertTrue($sut->setFetchMode(\PDO::FETCH_ASSOC));
    }

    function test_current()
    {
        $sut = (new NoopPdo())->prepare('SELECT 1 FROM dual');
        $sut->execute([]);
        $this->assertSame('current', $sut->current());
    }

    function test_next()
    {
        $sut = (new NoopPdo())->prepare('SELECT 1 FROM dual');
        $sut->execute([]);
        $this->assertNull($sut->next());
    }

    function test_key()
    {
        $sut = (new NoopPdo())->prepare('SELECT 1 FROM dual');
        $sut->execute([]);
        $this->assertNull($sut->key());
    }

    function test_valid()
    {
        $sut = (new NoopPdo())->prepare('SELECT 1 FROM dual');
        $sut->execute([]);
        $this->assertFalse($sut->valid());
    }

    function test_rewind()
    {
        $sut = (new NoopPdo())->prepare('SELECT 1 FROM dual');
        $sut->execute([]);
        $this->assertNull($sut->rewind());
    }
}
