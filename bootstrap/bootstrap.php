<?php declare(strict_types=1);

/**
 * 共通bootstrap
 */

use Psr\Log\LogLevel;

require __DIR__ . '/../vendor/autoload.php';

/*
 * 環境変数
 */
putenv(sprintf('APP_ROOT=%s', realpath(__DIR__ . '/..')));
putenv(sprintf('STORAGE_DIR=%s', getenv('APP_ROOT') . '/storage'));
putenv(sprintf('LOG_DIR=%s', getenv('STORAGE_DIR') . '/logs'));
putenv(sprintf('TMP_DIR=%s', getenv('STORAGE_DIR') . '/tmp'));
putenv(sprintf('LOCK_DIR=%s', getenv('STORAGE_DIR') . '/lock'));

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$dotenv->required('LOG_LEVEL')->allowedValues([
    LogLevel::DEBUG, LogLevel::INFO, LogLevel::NOTICE,
    LogLevel::WARNING, LogLevel::ERROR,
    LogLevel::CRITICAL, LogLevel::ALERT, LogLevel::EMERGENCY,
]);
$dotenv->required('APP_ROOT');
$dotenv->required('LOG_DIR');
$dotenv->required('TMP_DIR');

/*
 * php.ini
 */
ini_set('date.timezone', 'Asia/Tokyo');
