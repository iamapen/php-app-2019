<?php
/**
 * コンテナ設定
 * @return \Psr\Container\ContainerInterface
 */
declare(strict_types=1);

use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Acme\App\AppContainerInterface;

$container = (new DI\ContainerBuilder(\Acme\App\AppContainer::class))
    ->build();

/*
 * logger
 */
// アプリログ
$container->set(
    LoggerInterface::class,
    (new Logger('app'))
        ->pushHandler(
            new \Monolog\Handler\StreamHandler(
                __DIR__ . '/../../../storage/logs/unittest.log',
                getenv('LOG_LEVEL'),
                true,
                0666
            )
        )
);

// sqlログ
$container->set(
    AppContainerInterface::LOGGER_SQL,
    (new Logger('sql'))
        ->pushHandler(
            (new RotatingFileHandler(
                getenv('LOG_DIR') . '/sql.log',
                30,
                getenv('LOG_LEVEL'),
                true,
                0666
            ))->setFilenameFormat('{filename}_{date}', 'Ymd')
        )->pushProcessor(
            (new \Monolog\Processor\ProcessIdProcessor())
        )->pushProcessor(
            (new \Monolog\Processor\MemoryUsageProcessor())
        )
);

// DB
$container->set(AppContainerInterface::DB_SLAVE, DI\create(\Acme\Support\Database\Pdo\Pdo::class)
    ->constructor(
        sprintf(
            '%s:host=%s;port=%s;dbname=%s;charset=%s',
            getenv('DB_MAIN_MASTER_CONNECTION'),
            getenv('DB_MAIN_MASTER_HOST'), getenv('DB_MAIN_MASTER_PORT'),
            getenv('DB_MAIN_MASTER_DATABASE'), getenv('DB_MAIN_MASTER_CHARSET')
        ),
        getenv('DB_MAIN_MASTER_USERNAME'), getenv('DB_MAIN_MASTER_PASSWORD'),
        [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION, \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC, \PDO::ATTR_AUTOCOMMIT => false,]
    )
    ->lazy()
);

$container->set(AppContainerInterface::DB_SLAVE, DI\create(\Acme\Support\Database\Pdo\Pdo::class)
    ->constructor(
        sprintf(
            '%s:host=%s;port=%s;dbname=%s;charset=%s',
            getenv('DB_ADMIN_SLAVE_CONNECTION'),
            getenv('DB_ADMIN_SLAVE_HOST'), getenv('DB_ADMIN_SLAVE_PORT'),
            getenv('DB_ADMIN_SLAVE_DATABASE'), getenv('DB_ADMIN_SLAVE_CHARSET')
        ),
        getenv('DB_ADMIN_SLAVE_USERNAME'), getenv('DB_ADMIN_SLAVE_PASSWORD'),
        [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION, \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC, \PDO::ATTR_AUTOCOMMIT => false,]
    )
    ->lazy()
);

return $container;
