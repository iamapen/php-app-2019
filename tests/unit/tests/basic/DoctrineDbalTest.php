<?php declare(strict_types=1);

use Doctrine\DBAL\Query\QueryBuilder;
use Acme\Support\Database\DoctrineDbal\NullConnection;
use Acme\Support\Database\DoctrineDbal\NullMysqlDriver;

class DoctrineDbalTest extends \PHPUnit\Framework\TestCase
{
    function test_basic()
    {
        $dummyCon = new NullConnection(new NullMysqlDriver());
        $qb = new QueryBuilder($dummyCon);
        $this->assertSame(Doctrine\DBAL\Query\QueryBuilder::class, get_class($qb));

        $qb = new \Doctrine\DBAL\Query\QueryBuilder($dummyCon);
        $this->assertSame('SELECT col1, col2', $qb->select('col1, col2')->getSQL());

        // SELECTが付いてしまう
        $qb = new \Doctrine\DBAL\Query\QueryBuilder($dummyCon);
        $this->assertSame('SELECT  WHERE (delete_flg = 0) AND (foo_id = ?)', $qb->where('delete_flg = 0')->andWhere('foo_id = ?')->getSQL());
    }
}
