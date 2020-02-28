<?php declare(strict_types=1);

namespace Acme\App;

use Acme\Support\Database\Pdo\PdoInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

interface AppContainerInterface extends ContainerInterface
{
    const LOGGER_APP = \Psr\Log\LoggerInterface::class;
    const LOGGER_ERROR = 'LOGGER_ERROR';
    const LOGGER_SQL = 'LOGGER_SQL';

    const DB_MASTER = 'DB_MASTER';
    const DB_SLAVE = 'DB_SLAVE';

    public function dbMainMaster(): PdoInterface;

    public function dbMainSlave(): PdoInterface;

    public function loggerApp(): LoggerInterface;

    public function loggerSql(): LoggerInterface;
}