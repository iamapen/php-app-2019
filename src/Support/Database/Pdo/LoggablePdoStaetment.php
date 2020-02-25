<?php declare(strict_types=1);

namespace Acme\Support\Database\Pdo;

use Psr\Log\LoggerInterface;

class LoggablePdoStaetment extends \PDOStatement implements PdoStatementInterface
{
    /** @var LoggerInterface */
    private $logger;

    protected function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
        // TODO イベントでの実装かな。。
    }
}
