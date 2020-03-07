<?php

namespace Acme\Support\Database\DoctrineDbal;

class FactoryTest extends \PHPUnit\Framework\TestCase
{

    public function test_createMysqlQueryBuilder()
    {
        $qb = Factory::createMysqlQueryBuilder();
        $this->assertInstanceOf(\Doctrine\DBAL\Query\QueryBuilder::class, $qb);
    }
}
