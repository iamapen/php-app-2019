<?php declare(strict_types=1);

/**
 * ログ設定
 */

use Acme\App\AppContainerInterface as AppContainer;
use Monolog\Formatter;
use Monolog\Handler;
use Monolog\Logger;
use Monolog\Processor;
use Psr\Log\LoggerInterface;

return [
    AppContainer::LOGGER_ERROR =>
        (new Logger('error'))
            ->pushHandler(
                (new Handler\RotatingFileHandler(
                    sprintf('%s/%s', getenv('LOG_DIR'), 'error.log'),
                    30,
                    getenv('LOG_LEVEL'),
                    true,
                    0666
                ))
                    ->setFilenameFormat('{filename}_{date}', 'Ymd')
                    ->setFormatter(new Formatter\LineFormatter(null, null, true))
            )
            ->pushProcessor(new Processor\IntrospectionProcessor())
            ->pushProcessor(new Processor\ProcessIdProcessor()),
    LoggerInterface::class => function (AppContainer $c) {
        return $c->get(sprintf('LOGGER_%s', PHP_SAPI));
    },
    AppContainer::LOGGER_CLI =>
        (new Logger('cli'))
            ->pushHandler(
                (new Handler\RotatingFileHandler(
                    sprintf('%s/%s', getenv('LOG_DIR'), 'cli.log'),
                    30,
                    getenv('LOG_LEVEL'),
                    true,
                    0666
                ))
                    ->setFilenameFormat('{filename}_{date}', 'Ymd')
            )
            ->pushHandler(
                (new Handler\StreamHandler('php://stderr'))
                    ->setFormatter(new Formatter\LineFormatter(null, 'Y-m-d H:i:s.u', true))
            )
            ->pushProcessor(new Processor\IntrospectionProcessor())
            ->pushProcessor(new Processor\ProcessIdProcessor())
            ->pushProcessor(new Processor\MemoryPeakUsageProcessor())
            ->pushProcessor(new Processor\MemoryUsageProcessor()),
];
