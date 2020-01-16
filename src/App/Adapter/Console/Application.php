<?php
declare(strict_types=1);

namespace Acme\App\Adapter\Console;

use Monolog\ErrorHandler;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;

/**
 * CLIアプリケーションAdapter
 */
class Application extends \Symfony\Component\Console\Application
{
    /** @var ContainerInterface */
    private $container;

    public function __construct(ContainerInterface $container, string $name = 'UNKNOWN', string $version = 'UNKNOWN')
    {
        parent::__construct($name, $version);
        $this->container = $container;
    }

    public function registerCommand(string $command): Command
    {
        return $this->add($this->container->get($command));
    }

    public function registerErrorHandler(): Application
    {
        $logger = $this->container->get(LoggerInterface::class);
        ErrorHandler::register($logger);
        return $this;
    }
}
