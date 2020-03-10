<?php declare(strict_types=1);

namespace Acme\App;

use Acme\Support\Database\Pdo\PdoInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

interface AppContainerInterface extends ContainerInterface
{
    public const LOGGER_APP = \Psr\Log\LoggerInterface::class;
    public const LOGGER_ERROR = 'LOGGER_ERROR';
    public const LOGGER_SQL = 'LOGGER_SQL';
    public const LOGGER_CLI = 'LOGGER_cli';

    public const DB_MASTER = 'DB_MASTER';
    public const DB_SLAVE = 'DB_SLAVE';

    public function dbMainMaster(): PdoInterface;

    public function dbMainSlave(): PdoInterface;

    public function loggerApp(): LoggerInterface;

    public function loggerError(): LoggerInterface;

    public function loggerSql(): LoggerInterface;

    public function loggerCli(): LoggerInterface;
}
