<?php declare(strict_types=1);

namespace Acme\App;

use Acme\Support\Database\Pdo\PdoInterface;
use Psr\Log\LoggerInterface;

class AppContainer extends \DI\Container implements AppContainerInterface
{
    /** @throws \Psr\Container\ContainerExceptionInterface */
    public function loggerApp(): LoggerInterface
    {
        return $this->get(AppContainerInterface::LOGGER_APP);
    }

    /** @throws \Psr\Container\ContainerExceptionInterface */
    public function loggerSql(): LoggerInterface
    {
        return $this->get(AppContainerInterface::LOGGER_SQL);
    }

    /** @throws \Psr\Container\ContainerExceptionInterface */
    public function dbMainMaster(): PdoInterface
    {
        return $this->get(AppContainerInterface::DB_MASTER);
    }

    /** @throws \Psr\Container\ContainerExceptionInterface */
    public function dbMainSlave(): PdoInterface
    {
        return $this->get(AppContainerInterface::DB_SLAVE);
    }
}
