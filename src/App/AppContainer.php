<?php declare(strict_types=1);

namespace Acme\App;

use Acme\Support\Database\Pdo\PdoInterface;
use Psr\Log\LoggerInterface;

class AppContainer extends \DI\Container implements AppContainerInterface
{
    public function loggerApp(): LoggerInterface
    {
        return $this->get(AppContainerInterface::LOGGER_APP);
    }

    public function loggerSql(): LoggerInterface
    {
        return $this->get(AppContainerInterface::LOGGER_SQL);
    }

    public function dbMainMaster(): PdoInterface
    {
        return $this->get(AppContainerInterface::DB_MASTER);
    }

    public function dbMainSlave(): PdoInterface
    {
        return $this->get(AppContainerInterface::DB_SLAVE);
    }
}
