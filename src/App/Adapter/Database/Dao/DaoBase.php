<?php declare(strict_types=1);

namespace Acme\App\Adapter\Database\Dao;

use Acme\Support\Database\Pdo\PdoInterface;
use Acme\Support\Database\Pdo\PdoStatementInterface;
use Acme\Support\Database\SqlDumper;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

abstract class DaoBase
{
    /** @var PdoInterface */
    protected $pdo;

    /** @var LoggerInterface */
    private $logger;

    public function __construct(PdoInterface $pdo, ?LoggerInterface $logger = null)
    {
        $this->pdo = $pdo;

        if ($logger === null) {
            $logger = new NullLogger();
        }
        $this->logger = $logger;
    }

    /**
     * @param $statement
     * @param array|null $input_parameters
     * @return PdoStatementInterface
     */
    protected function prepareAndExecute($statement, ?array $input_parameters = null)
    {
        $this->logger->debug(SqlDumper::dump(
            preg_replace('/[\p{C}\p{Z}]++/u', ' ', $statement),
            $input_parameters
        ));
        $stmt = $this->pdo->prepare($statement);
        $stmt->execute($input_parameters);
        return $stmt;
    }

    /**
     * @return \Doctrine\DBAL\Query\QueryBuilder
     */
    protected function createQueryBuilder(): \Doctrine\DBAL\Query\QueryBuilder
    {
        return \Acme\Support\Database\DoctrineDbal\Factory::createMysqlQueryBuilder();
    }
}
