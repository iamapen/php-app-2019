<?php
declare(strict_types=1);

class EnvTest extends \PHPUnit\Framework\TestCase
{
    function test_basic()
    {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../../');
        $dotenv->load();

        $ex = 'hoge';
        $this->assertSame($ex, getenv('HOGE'));
        $this->assertSame($ex, $_ENV['HOGE']);
        $this->assertSame($ex, $_SERVER['HOGE']);
    }

    function test_nested() {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../../');
        $dotenv->load();

        $ex = 'hoge/fuga';
        $this->assertSame($ex, getenv('FUGA'));
        $this->assertSame($ex, $_ENV['FUGA']);
        $this->assertSame($ex, $_SERVER['FUGA']);
    }

    function test_required_ok() {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../../');
        $dotenv->load();

        $dotenv->required(['HOGE', 'EMPTY_VAR']);
        $this->assertTrue(true);
    }

    function test_required_fail() {
        $this->expectException(Dotenv\Exception\ValidationException::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage('One or more environment variables failed assertions: REQUIRED_VAR is missing.');

        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../../');
        $dotenv->load();
        $dotenv->required('REQUIRED_VAR');
    }

    function test_notEmpty_ok() {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../../');
        $dotenv->load();
        $dotenv->required('HOGE')->notEmpty();
        $this->assertTrue(true);
    }

    function test_notEmpty_fail() {
        $this->expectException(Dotenv\Exception\ValidationException::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage("One or more environment variables failed assertions: EMPTY_VAR is empty.");

        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../../');
        $dotenv->load();
        $dotenv->required('EMPTY_VAR')->notEmpty();
        $this->assertTrue(true);
    }
}
