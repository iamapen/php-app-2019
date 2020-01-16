<?php
/**
 * コンテナ設定
 * @return \Psr\Container\ContainerInterface
 */
declare(strict_types=1);

use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

$container = (new DI\ContainerBuilder())
    ->build();

$container->set(
    LoggerInterface::class,
    (new Logger('app'))
        ->pushHandler(
            (new RotatingFileHandler(
                __DIR__ . '/../storage/logs/' . PHP_SAPI . '.log',
                30,
                getenv('LOG_LEVEL'),
                true,
                0666
            ))
                ->setFilenameFormat('{filename}_{date}', 'Ymd')
        )
);

return $container;
