<?php declare(strict_types=1);

namespace Acme\Support\Database\Pdo;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class LoggablePdo extends \PDO implements PdoInterface
{
    /** @var LoggerInterface */
    private $logger;

    public function __construct($dsn, $username = null, $passwd = null, $options = null, ?LoggerInterface $logger = null)
    {
        parent::__construct($dsn, $username, $passwd, $options);

        if ($logger === null) {
            $logger = new NullLogger();
        }
        $this->logger = $logger;
        $this->setAttribute(\PDO::ATTR_STATEMENT_CLASS, [LoggablePdoStaetment::class, [$this->logger]]);
    }

    public function query()
    {
        $this->logger->debug(func_get_arg(0));
        return parent::query(...func_get_args());
    }

    public function exec($query)
    {
        $this->logger->debug($query);
        return parent::exec($query);
    }
}
