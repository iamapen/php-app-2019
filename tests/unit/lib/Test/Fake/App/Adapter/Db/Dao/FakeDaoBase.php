<?php declare(strict_types=1);

namespace Acme\Test\Fake\App\Adapter\Db\Dao;

use Acme\App\Adapter\Database\Dao\DaoBase;

class FakeDaoBase extends DaoBase
{
    public function passPrepareAndExecute($statement, ?array $input_parameters = null)
    {
        return $this->prepareAndExecute($statement, $input_parameters);
    }

    public function passCreateQueryBuilder() {
        return $this->createQueryBuilder();
    }
}
