<?php declare(strict_types=1);

use Acme\App\Adapter\Database\Pdo\AppPdo;
use Acme\App\AppContainerInterface;

return [
    AppContainerInterface::DB_MASTER => DI\factory(function (AppContainerInterface $c) {
        return AppPdo::asReconnectable(
            sprintf(
                'mysql:host=%s;dbname=%s;charset=utf8mb4',
                getenv('DB_MASTER_HOST'),
                getenv('DB_MASTER_DATABASE')
            ),
            getenv('DB_MASTER_USERNAME'), getenv('DB_MASTER_PASSWORD'),
            [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            ]
        );
    }),
];
