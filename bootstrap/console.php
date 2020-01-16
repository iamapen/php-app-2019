<?php
/**
 * CLI bootstrap
 * @return \Symfony\Component\Console\Application
 */
declare(strict_types=1);
require __DIR__ . '/../vendor/autoload.php';

use Psr\Log\LogLevel;

// 環境変数
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();
$dotenv->required('LOG_LEVEL')->allowedValues([
    LogLevel::DEBUG, LogLevel::INFO, LogLevel::NOTICE,
    LogLevel::WARNING, LogLevel::ERROR,
    LogLevel::CRITICAL, LogLevel::ALERT, LogLevel::EMERGENCY,
]);

$app = new Acme\App\Adapter\Console\Application(
    require __DIR__ . '/container.php'
);
$app->registerErrorHandler();

// バッチコマンド登録
$app->registerCommand(\Acme\App\Adapter\Console\Command\HelloCommand::class);

return $app;
