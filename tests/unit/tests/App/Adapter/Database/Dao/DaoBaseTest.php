<?php declare(strict_types=1);

namespace Acme\App\Adapter\Database\Dao;

use Acme\Support\Database\Pdo\NoopPdo;
use Acme\Test\Fake\App\Adapter\Db\Dao\FakeDaoBase;
use Acme\Test\TestCase\BaseTestCase;
use Doctrine\DBAL\Query\QueryBuilder;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class DaoBaseTest extends BaseTestCase
{
    function test_prepareAndExecute_ログが記録されること()
    {
        $fp = fopen('php://temp', 'wb');
        $logger = (new Logger('ut'))->setHandlers([
            (new StreamHandler($fp))->setFormatter(new LineFormatter('%message%')),
        ]);

        $sut = new FakeDaoBase(new NoopPdo(), $logger);
        $sut->passPrepareAndExecute('SELECT 1 FROM dual WHERE 1 = ?', ['1']);

        fseek($fp, 0);
        $logged = fgets($fp);
        $this->assertSame("SELECT 1 FROM dual WHERE 1 = '1'", $logged);
    }

    function test_prepareAndExecute_ログの2つ以上のスペースが1つになること()
    {
        $fp = fopen('php://temp', 'wb');
        $logger = (new Logger('ut'))->setHandlers([
            (new StreamHandler($fp))->setFormatter(new LineFormatter('%message%')),
        ]);

        $sut = new FakeDaoBase(new NoopPdo(), $logger);
        $sut->passPrepareAndExecute('SELECT 1  FROM dual  WHERE 1 = ?', ['2']);

        fseek($fp, 0);
        $logged = fgets($fp);
        $this->assertSame("SELECT 1 FROM dual WHERE 1 = '2'", $logged);
    }

    function test_createMysqlQueryBuilder()
    {
        $sut = new FakeDaoBase(new NoopPdo());

        $this->assertInstanceOf(QueryBuilder::class, $sut->passCreateQueryBuilder());
    }
}
