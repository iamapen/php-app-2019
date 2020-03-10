<?php declare(strict_types=1);

/**
 * コンテナ設定
 * @return \Psr\Container\ContainerInterface
 */

use Acme\App\AppContainer;
use Acme\App\AppContainerHolder;
use Acme\App\AppContainerInterface;

$builder = (new DI\ContainerBuilder(AppContainer::class));

$builder->addDefinitions(__DIR__ . '/container/log.php');

// TODO session
// TODO template engine
// TODO DB connection

/* @var $container AppContainerInterface */
$container = $builder->build();
\Monolog\ErrorHandler::register($container->loggerError());
AppContainerHolder::init($container);

return $container;
