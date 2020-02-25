<?php declare(strict_types=1);

namespace Acme\Support\Database\DoctrineDbal;

use Doctrine\DBAL\Query\QueryBuilder;

class Factory
{
    public static function createMysqlQueryBuilder(): QueryBuilder
    {
        return new QueryBuilder(
            new NullConnection(new NullMysqlDriver())
        );
    }
}
