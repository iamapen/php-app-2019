<?php declare(strict_types=1);

namespace Acme\Support\Database\Pdo;

class Pdo extends \PDO implements PdoInterface
{
    public function __construct($dsn, $username = null, $passwd = null, $options = null)
    {
        parent::__construct($dsn, $username, $passwd, $options);
        $this->setAttribute(\PDO::ATTR_STATEMENT_CLASS, [PdoStaetment::class, []]);
    }
}
