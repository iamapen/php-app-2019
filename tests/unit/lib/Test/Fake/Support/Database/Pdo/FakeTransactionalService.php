<?php declare(strict_types=1);

namespace Acme\Test\Fake\Support\Database\Pdo;

use Acme\Support\Database\Pdo\PdoTransactionInterface;
use Acme\Support\Database\Pdo\Transactional;

class FakeTransactionalService
{
    use Transactional;

    public function __construct(PdoTransactionInterface $dbMaster)
    {
        $this->transactional = $dbMaster;
    }
}
