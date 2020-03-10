<?php declare(strict_types=1);

use Acme\Support\Cache\ArrayLruSimpleCache;

class ArrayLruSimpleCacheTest extends \PHPUnit\Framework\TestCase
{
    function test_construct()
    {
        $sut = new ArrayLruSimpleCache(3);
        $this->assertInstanceOf(\Psr\SimpleCache\CacheInterface::class, $sut);
    }

    function test_construct_invalid()
    {
        $this->expectException(\Psr\SimpleCache\CacheException::class);
        $this->expectExceptionMessage('Size must be a positive integer, "-1" given');
        $sut = new ArrayLruSimpleCache(-1);
    }

    function test_get()
    {
        $sut = new ArrayLruSimpleCache(3);
        $sut->set('foo', 'bar');

        $this->assertSame('bar', $sut->get('foo'));
        $this->assertSame('bar', $sut->get('foo', 'FOO'));

        $this->assertSame(null, $sut->get('undef'));
        $this->assertSame('', $sut->get('undef', ''));
    }

    function test_set()
    {
        $sut = new ArrayLruSimpleCache(3);
        $this->assertSame(null, $sut->get('foo'));

        $sut->set('foo', 'bar');
        $this->assertSame('bar', $sut->get('foo'));

        $sut->set('foo', 'overrided');
        $this->assertSame('overrided', $sut->get('foo'));
    }

    function test_delete()
    {
        $sut = new ArrayLruSimpleCache(3);
        $sut->set('foo', 'bar');

        $this->assertSame('bar', $sut->get('foo'));
        $this->assertTrue($sut->delete('foo'));
        $this->assertSame(null, $sut->get('foo'));

        $this->assertTrue($sut->delete('undef'));
    }

    function test_clear()
    {
        $sut = new ArrayLruSimpleCache(3);
        $sut->set('foo', 'bar');
        $sut->set('piyo', 'puyo');

        $this->assertSame('bar', $sut->get('foo'));
        $this->assertSame('puyo', $sut->get('piyo'));
        $sut->clear();
        $this->assertSame(null, $sut->get('foo'));
        $this->assertSame(null, $sut->get('piyo'));
    }

    function test_getMultiple_byArray()
    {
        $sut = new ArrayLruSimpleCache(3);
        $sut->set('foo', 'bar');
        $sut->set('piyo', 'puyo');
        $sut->set('hoge', 'fuga');

        $this->assertSame(['bar', 'fuga'], $sut->getMultiple(['foo', 'hoge']));
    }

    function test_getMultiple_byIterable()
    {
        $sut = new ArrayLruSimpleCache(3);
        $sut->set('foo', 'bar');
        $sut->set('piyo', 'puyo');
        $sut->set('hoge', 'fuga');

        $iterable = new SplFixedArray(2);
        $iterable[0] = 'foo';
        $iterable[1] = 'hoge';

        $this->assertSame(['bar', 'fuga'], $sut->getMultiple($iterable));
    }

    function test_getMultiple_invalid()
    {
        $this->expectException(\Psr\SimpleCache\CacheException::class);
        $this->expectExceptionMessage('Cache keys must be array or Traversable, "string" given');

        $sut = new ArrayLruSimpleCache(3);
        $sut->getMultiple('invalid');
    }

    function test_setMultiple_byArray()
    {
        $sut = new ArrayLruSimpleCache(3);

        $this->assertSame(null, $sut->get('foo'));
        $this->assertSame(null, $sut->get('piyo'));
        $this->assertSame(null, $sut->get('hoge'));

        $sut->setMultiple(['foo' => 'bar', 'piyo' => 'puyo', 'hoge' => 'fuga']);
        $this->assertSame('bar', $sut->get('foo'));
        $this->assertSame('puyo', $sut->get('piyo'));
        $this->assertSame('fuga', $sut->get('hoge'));
    }

    function test_setMultiple_byIterable()
    {
        $sut = new ArrayLruSimpleCache(3);

        $this->assertSame(null, $sut->get('foo'));
        $this->assertSame(null, $sut->get('piyo'));
        $this->assertSame(null, $sut->get('hoge'));

        $iterable = new SplFixedArray(3);
        $iterable[0] = 'bar';
        $iterable[1] = 'puyo';
        $iterable[2] = 'fuga';

        $sut->setMultiple($iterable);
        $this->assertSame('bar', $sut->get('0'));
        $this->assertSame('puyo', $sut->get('1'));
        $this->assertSame('fuga', $sut->get('2'));
    }

    function test_setMultiple_invalid()
    {
        $this->expectException(\Psr\SimpleCache\CacheException::class);
        $this->expectExceptionMessage('Cache values must be array or Traversable, "string" given');

        $sut = new ArrayLruSimpleCache(3);
        $sut->setMultiple('invalid');
    }

    function test_deleteMultiple_byArray()
    {
        $sut = new ArrayLruSimpleCache(3);
        $sut->set('foo', 'bar');
        $sut->set('piyo', 'puyo');
        $sut->set('hoge', 'fuga');

        $this->assertTrue($sut->deleteMultiple(['foo', 'hoge']));
        $this->assertSame(null, $sut->get('foo'));
        $this->assertSame('puyo', $sut->get('piyo'));
        $this->assertSame(null, $sut->get('hoge'));
    }

    function test_deleteMultiple_byIterable()
    {
        $sut = new ArrayLruSimpleCache(3);
        $sut->set('foo', 'bar');
        $sut->set('piyo', 'puyo');
        $sut->set('hoge', 'fuga');

        $iterable = new SplFixedArray(2);
        $iterable[0] = 'foo';
        $iterable[1] = 'hoge';

        $this->assertTrue($sut->deleteMultiple($iterable));
        $this->assertSame(null, $sut->get('foo'));
        $this->assertSame('puyo', $sut->get('piyo'));
        $this->assertSame(null, $sut->get('hoge'));
    }

    function test_deleteMultiple_invalid()
    {
        $this->expectException(\Psr\SimpleCache\CacheException::class);
        $this->expectExceptionMessage('Cache keys must be array or Traversable, "string" given');

        $sut = new ArrayLruSimpleCache(3);
        $sut->deleteMultiple('invalid');
    }

    function test_has()
    {
        $sut = new ArrayLruSimpleCache(3);
        $sut->set('foo', 'bar');

        $this->assertTrue($sut->has('foo'));

        $sut->delete('foo');
        $this->assertFalse($sut->has('foo'));

        $this->assertFalse($sut->has('undef'));
    }

    function test_getSize()
    {
        $sut = new ArrayLruSimpleCache(3);

        $this->assertSame(0, $sut->getSize());

        $sut->set('foo', 'bar');
        $this->assertSame(1, $sut->getSize());
    }

    function test_getAll()
    {
        $sut = new ArrayLruSimpleCache(3);
        $sut->set('a', 'A');
        $sut->set('b', 'B');

        $ex = ['a' => 'A', 'b' => 'B'];
        $this->assertSame($ex, $sut->getAll());
    }

    function test_LRUなこと()
    {
        $sut = new ArrayLruSimpleCache(3);
        $sut->set('a', 'A');
        $sut->set('b', 'B');
        $sut->set('c', 'C');
        $this->assertTrue($sut->has('b'));

        // 2個足しても大丈夫
        $this->assertSame('B', $sut->get('b'));
        $sut->set('D', 'D');
        $sut->set('E', 'E');
        $this->assertTrue($sut->has('b'));

        // 3個目足すと消える
        $sut->set('F', 'F');
        $this->assertFalse($sut->has('b'));
    }

    function test_LRUでcallbackが実行されること()
    {
        $displayRotatedItem = null;
        $displayRotatedKey = null;
        $sut = new ArrayLruSimpleCache(3, function ($key, $item) use (&$displayRotatedKey, &$displayRotatedItem) {
            $displayRotatedKey = $key;
            $displayRotatedItem = $item;
        });
        $this->assertSame(null, $displayRotatedItem);

        // 3個足しても大丈夫
        $sut->setMultiple(['a', 'b', 'c']);
        $this->assertSame(null, $displayRotatedItem);
        // 先頭を触っておく
        $sut->get(0);

        // 4個目でLRU発生
        $sut->set('F', 'F');
        $this->assertSame('b', $displayRotatedItem);
        $this->assertSame(1, $displayRotatedKey);
    }

    function test_getRotatedCount()
    {
        $sut = new ArrayLruSimpleCache(3);
        $this->assertSame(0, $sut->getRotatedCount());

        // 3個足しても大丈夫
        $sut->setMultiple(['a', 'b', 'c']);
        $this->assertSame(0, $sut->getRotatedCount());

        // 4個目でLRU発生
        $sut->set('F', 'F');
        $this->assertFalse($sut->has('b'));
        $this->assertSame(1, $sut->getRotatedCount());

        // さらに2回発生
        $sut->setMultiple(['X', 'Y']);
        $this->assertSame(3, $sut->getRotatedCount());
    }
}
